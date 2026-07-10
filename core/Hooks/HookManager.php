<?php

namespace Core\Hooks;

use Core\Contracts\HookInterface;
use Core\Exceptions\HookException;

class HookManager implements HookInterface
{
    /** @var array<string, array<int, array{callback:callable, priority:int, acceptedArgs:int}>> */
    private array $actions = [];

    /** @var array<string, array<int, array{callback:callable, priority:int, acceptedArgs:int}>> */
    private array $filters = [];

    public function doAction(string $hook, mixed ...$args): void
    {
        foreach ($this->actionsFor($hook) as $item) {
            $this->call($item['callback'], $args, $item['acceptedArgs']);
        }
    }

    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed
    {
        foreach ($this->filtersFor($hook) as $item) {
            $args = array_merge([$value], $args);
            $value = $this->call($item['callback'], $args, $item['acceptedArgs']);
        }

        return $value;
    }

    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->actions[$hook][] = [
            'callback' => $callback,
            'priority' => $priority,
            'acceptedArgs' => $acceptedArgs,
        ];

        $this->sort($this->actions[$hook]);
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->filters[$hook][] = [
            'callback' => $callback,
            'priority' => $priority,
            'acceptedArgs' => $acceptedArgs,
        ];

        $this->sort($this->filters[$hook]);
    }

    public function removeAction(string $hook, callable $callback, int $priority = 10): bool
    {
        if (!isset($this->actions[$hook])) {
            return false;
        }

        foreach ($this->actions[$hook] as $index => $item) {
            if ($item['priority'] !== $priority) {
                continue;
            }

            if ($item['callback'] === $callback || $this->sameCallable($item['callback'], $callback)) {
                unset($this->actions[$hook][$index]);

                if ($this->actions[$hook] === []) {
                    unset($this->actions[$hook]);
                }

                return true;
            }
        }

        return false;
    }

    public function removeFilter(string $hook, callable $callback, int $priority = 10): bool
    {
        if (!isset($this->filters[$hook])) {
            return false;
        }

        foreach ($this->filters[$hook] as $index => $item) {
            if ($item['priority'] !== $priority) {
                continue;
            }

            if ($item['callback'] === $callback || $this->sameCallable($item['callback'], $callback)) {
                unset($this->filters[$hook][$index]);

                if ($this->filters[$hook] === []) {
                    unset($this->filters[$hook]);
                }

                return true;
            }
        }

        return false;
    }

    public function hasAction(string $hook): bool
    {
        return !empty($this->actions[$hook]);
    }

    private function actionsFor(string $hook): array
    {
        return $this->sorted($this->actions[$hook] ?? []);
    }

    private function filtersFor(string $hook): array
    {
        return $this->sorted($this->filters[$hook] ?? []);
    }

    /** @param array<int, array{priority:int}> $items */
    private function sort(array &$items): void
    {
        usort($items, static fn (array $a, array $b) => $a['priority'] <=> $b['priority']);
    }

    /** @return array<int, array{callback:callable, priority:int, acceptedArgs:int}> */
    private function sorted(array $items): array
    {
        $this->sort($items);

        return $items;
    }

    /**
     * @param callable $callback
     * @param array<int, mixed> $args
     * @return mixed
     */
    private function call(callable $callback, array $args, int $acceptedArgs)
    {
        $args = array_slice($args, 0, $acceptedArgs, true);

        if ($args === []) {
            $result = $callback();
        } else {
            $result = $callback(...$args);
        }

        return $result;
    }

    private function sameCallable(callable $a, callable $b): bool
    {
        if (is_array($a) && is_array($b)) {
            return $a[0] === $b[0] && $a[1] === $b[1];
        }

        if (is_string($a) && is_string($b)) {
            return $a === $b;
        }

        return false;
    }
}
