# Arquitetura do Core — Framework

Documento oficial de arquitetura do núcleo. Toda implementação subsequente deve seguir as definições abaixo para evitar acoplamento, retrabalho e inconsistências.

## 1. Visão

O Core deve ser enxuto, estável e responsável apenas por:
- gerenciar pacotes,
- coordenar descoberta/registro/carregamento,
- prover contratos e contratos-cliente,
- fornecer extensibilidade por hooks/events,
- isolar o núcleo de regras de negócio.

Nada de lógica de domínio do Blog/Ecommerce/etc. dentro do Core.

## 2. Componentes

ASCII diagram:

```
+-------------------------------------------------------------+
|                        Application                           |
|                                                             |
|  +----------+  +----------+  +----------+  +-------------+  |
|  | Registry |  | HookMgr  |  | EventBus |  | PackageMgr  |  |
|  +----+-----+  +----+-----+  +----+-----+  +------+------+  |
|        |             |             |                |        |
|        v             v             v                v        |
|  +------------+  +---------+  +-----------+  +------------+  |
|  | Contracts  |  | Plugins |  | Listeners |  | Modules     |  |
|  +------------+  +---------+  +-----------+  +------------+  |
|                                            +------------+  |
|                                            | Themes      |  |
|                                            +------------+  |
+-------------------------------------------------------------+
```

Principais blocos:
- **Registry**: ponto único global de registros (módulos, plugins, temas, rotas, menus, widgets, assets, traduções, permissões, comandos, schedules).
- **Hook Manager**: actions/filters (sistema semelhante ao WordPress).
- **Event Bus**: comunicação desacoplada via Events/Listeners do Laravel com tipagem forte.
- **Package Manager**: coordena Module/Plugin/Theme Managers. Responsável por resolução de dependências, instalação, atualização, desinstalação.
- **Contracts**: interfaces padrão ISO para pacotes e contratos do Core.
- **Managers**: ModuleManager, PluginManager, ThemeManager. Cada um gerencia ciclo de vida do seu tipo de pacote.

## 3. Fluxos

### 3.1 Bootstrap

1. Laravel carrega `bootstrap/app.php`.
2. Core registra apenas `CoreServiceProvider`.
3. `CoreServiceProvider` inicializa `Registry`.
4. Package Manager lê `installed_packages` e prepara Coleção de pacotes instalados.
5. Package Manager executa dependency resolver.
6. Package Manager registra providers dos pacotes no container/app.
7. Cada manager carrega rotas/config/migrations/seeders/assets do pacote no momento apropriado.
8. Não há registro hardcoded de providers no `bootstrap/app.php`.

### 3.2 Descoberta

1. `Package Manager` varrerá diretórios oficiais:
   - `modules/`
   - `plugins/`
   - `themes/`
2. Em cada diretório, buscará `module.json` válido.
3. Validará schema mínimo do manifesto.
4. Indexará básico: nome, slug, versão, provider, tipo, dependências.
5. Descoberta não implica carregamento automático — só indexação.

### 3.3 Carregamento

1. `Package Manager` lê `installed_packages`.
2. Para cada item instalado:
   - valida se manifesto existe;
   - valida dependências;
   - instancia provider do pacote;
   - chama `register()` e `boot()` quando aplicado por estágio;
   - registra rotas/views/migrations/config conforme manifesto;
   - registra hooks/permissions/menus/widgets/assets no Registry.
3. Ordem de carregamento:
   - core first;
   - depois módulos;
   - depois plugins;
   - depois temas.

### 3.4 Instalação

1. `InstallerInterface` com método `install(string $type, string $slug): InstallResult`.
2. Implementações por tipo:
   - `ModuleInstaller`
   - `PluginInstaller`
   - `ThemeInstaller`
3. Fluxo genérico:
   - recebe tipo + slug;
   - valida manifest;
   - valida dependências (DependencyResolver);
   - copia/resolva arquivos para destino correto;
   - grava em `installed_packages`;
   - executa migrations declaradas;
   - executa seeders declarados;
   - registra provider e ativa hooks/assets/routes;
   - retorna sucesso ou falha estruturada.

### 3.5 Atualização

1. Compara `module.json` novo com registro em `installed_packages`.
2. Se houver migrations novas, executa apenas as novas.
3. Se houver seeders declarados para upgrade, executa quando idempotente.
4. Atualiza metadados no `installed_packages`.
5. Permite rollback por registro de versão anterior.

### 3.6 Desinstalação

1. Valida estado do pacote (ativo/sem dependentes).
2. Detecta seeders de desinstalação se declarados.
3. Permite migração reversa se pacote declarar `down` migrations.
4. Remove registros no Registry.
5. Remove entry em `installed_packages`.
6. Remove arquivos do pacote ou marca como órfão para limpeza manual.

## 4. Contracts

Contratos do Core:

- `ModuleInterface`
- `PluginInterface`
- `ThemeInterface`
- `HookInterface`
- `RegistryInterface`
- `InstallerInterface`
- `ManagerInterface`

Contratos adicionais:
- `AssetInterface`
- `PermissionInterface`
- `RouteRegistryInterface`
- `TranslationInterface`
- `WidgetInterface`
- `MenuInterface`
- `CommandInterface`
- `SchedulerInterface`
- `ConfigInterface`

Regra:
- Nenhum componente pode depender de implementação concreta fora do Core por meio de coupling forte.
- O Core referencia apenas Contracts.

## 5. Registry

Responsabilidades:
- Registrar instâncias e metadados de pacotes, rotas, menus, widgets, assets, traduções, permissões, comandos e schedules.
- Proporcionar uma API única para consulta do estado do framework.
- Evitar globals e singletons espalhados.

Regras:
- Registry é único por request.
- Qualquer registro deve ser reversível (unregister).
- Registry não contém lógica de negócio.
- Registry isola nomes por tipo para evitar colisões.

## 6. Managers

Cada Manager:
- Respeita `ManagerInterface`.
- Controla ciclo de vida do seu tipo de pacote.
- Usa `Registry` para expor dados.
- Nunca acessa diretamente outro Manager; comunicação é por Hooks/Events ou Registry consultado.

Regra de ouro:
- Managers são finos.
- Regras de negócio vivem em Services/Actions dentro do pacote.

## 7. Hook Manager

Responsabilidades:
- Gerenciar actions (executam lógica e retornam void/enriched data).
- Gerenciar filters (entrada → saída transformada).
- Suportar prioridades.
- Suportar remoção de hooks.

Contrato:
- `HookInterface::doAction(string $hook, mixed ...$args): void`
- `HookInterface::applyFilters(string $hook, mixed $value, mixed ...$args): mixed`

Rainha da extensibilidade. Plugins devem usar hooks antes de editar código core.

## 8. Event Bus

Responsabilidades:
- Events tipados do Laravel.
- Listeners síncronos/assíncronos.
- Fila dispatcheável quando configurada.

Diferença entre Hook e Event:
- Hook: propósito genérico, curto, idiomático WordPress.
- Event: propósito de domínio, rastreável, pode enfileirar.

## 9. Installers e Dependency Resolver

- `InstallerInterface` define contrato comum.
- `Dependency Resolver` valida grafos de dependência antes de instalar/atualizar.
- Evita instalação que deixe o sistema em estado inconsistente.

## 10. Asset Manager

Responsabilidades:
- Registrar e publicar assets por pacote.
- Suportar Vite/Mix/manual.
- Controlar ordem, dependências e versões.
- Integrar temas e módulos.

## 11. Permission / Route / Translation / Command / Scheduler / Menu / Widget Registries

Cada Registry:
- É uma fatia temática do Registry central.
- Provê métodos tipados.
- Pode ser carregado sob demanda.
- Nunca deve ser duplicado.

## 12. Anti-acoplamento

Regras:
- Nenhum Manager conhece implementação concreta de outro Manager.
- Nenhum pacote pode exigir alteração código core para integrar-se.
- Qualquer integração cross-package deve usar:
  - Contracts,
  - Hooks,
  - Events,
  - Registry consultas.
- Sem `instanceof FactoryConcreta` espalhado.
- Sem `require` de módulos concretos dentro do Core.

## 13. Estrutura de diretórios do Core

```
core/
- Contracts/
- Registry/
- Managers/
- Hooks/
- Events/
- Installers/
- Exceptions/
- Support/
```

## 14. Manifesto oficial: module.json schema baseline

```json
{
  "name": "string",
  "slug": "string",
  "version": "semver",
  "description": "string?",
  "author": "string?",
  "license": "string?",
  "type": "module|plugin|theme",

  "provider": "string",
  "providers": ["string"],

  "requires": {
    "core": "semver",
    "php": "semver?",
    "laravel": "semver?",
    "packages": {
      "slug": "semver"
    }
  },

  "permissions": [
    { "slug": "string", "name": "string", "module": "string", "description": "string?" }
  ],

  "commands": ["string"],
  "aliases": ["string"],
  "assets": {
    "css": ["string"],
    "js": ["string"],
    "vite": { "entry": "string", "publicDir": "string?" }
  },

  "config": {
    "key": "default"
  },

  "routes": ["web|api|console"],
  "views": ["string"],
  "translations": ["string"],

  "migrations": ["string"],
  "seeders": ["string"],

  "hooks": {
    "actions": ["string"],
    "filters": ["string"]
  },

  "widgets": ["string"],
  "menus": ["string"]
}
```

## 15. Decisões firmes

- Laravel 11 forever até indicar upgrade formal.
- Sem repository pattern por padrão para Eloquent.
- Services/Actions surgem por complexidade, não por padrão.
- Hooks > Events para extensibilidade de plugins.
- Registry como ponto único.
- Manifesto rico é contrato de primeira classe.
- Phased rollout por Fases 1..7; sem nova features de negócio até Fase 3 básica pronta.
- Fase 4 entrega Hook Manager e Event Bus como pontos de extensibilidade do Core.
