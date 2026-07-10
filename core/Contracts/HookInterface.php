<?php

namespace Core\Contracts;

interface HookInterface
{
    /**
     * Executa uma action e retorna void.
     *
     * @return void
     */
    public function doAction(string $hook, mixed ...$args): void;

    /**
     * Aplica filtros em um valor.
     *
     * @return mixed
     */
    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed;

    /**
     * Registra uma action.
     */
    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void;

    /**
     * Registra um filter.
     */
    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void;

    /**
     * Remove uma action registrada.
     */
    public function removeAction(string $hook, callable $callback, int $priority = 10): bool;

    /**
     * Remove um filter registrado.
     */
    public function removeFilter(string $hook, callable $callback, int $priority = 10): bool;

    /**
     * Verifica se existe ao menos uma action registrada para o hook.
     */
    public function hasAction(string $hook): bool;
}
