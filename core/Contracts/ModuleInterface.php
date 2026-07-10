<?php

namespace Core\Contracts;

/**
 * @method string name()
 * @method string slug()
 * @method string version()
 * @method string description()
 * @method string author()
 * @method string license()
 * @method string type()
 * @method array requires()
 * @method string provider()
 * @method array providers()
 * @method array aliases()
 * @method array commands()
 * @method array assets()
 * @method array config()
 * @method array routes()
 * @method array views()
 * @method array translations()
 * @method array migrations()
 * @method array seeders()
 * @method array hooks()
 * @method array widgets()
 * @method array menus()
 * @method array permissions()
 * @method string path(): diretório raiz do pacote
 * @method array json(): dados crus do module.json
 */
interface ModuleInterface
{
    public function name(): string;

    public function slug(): string;

    public function version(): string;

    public function type(): string;

    public function path(): string;

    public function provider(): string;

    public function providers(): array;

    public function requires(): array;
}
