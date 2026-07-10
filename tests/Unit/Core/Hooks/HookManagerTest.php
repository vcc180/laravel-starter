<?php

namespace Tests\Unit\Core\Hooks;

use Core\Hooks\HookManager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class HookManagerTest extends TestCase
{
    #[Test]
    public function it_registers_and_fires_actions_sorted_by_priority(): void
    {
        $manager = new HookManager();
        $order = [];

        $manager->addAction('init', function () use (&$order) {
            $order[] = 'high';
        }, priority: 20);

        $manager->addAction('init', function () use (&$order) {
            $order[] = 'low';
        }, priority: 5);

        $manager->doAction('init');

        $this->assertSame(['low', 'high'], $order);
    }

    #[Test]
    public function it_applies_filters_and_passes_value_through(): void
    {
        $manager = new HookManager();

        $manager->addFilter('title', fn (string $value) => strtoupper($value));
        $manager->addFilter('title', fn (string $value) => $value.'!');

        $result = $manager->applyFilters('title', 'hello');

        $this->assertSame('HELLO!', $result);
    }

    #[Test]
    public function it_removes_an_action_by_callback_and_priority(): void
    {
        $manager = new HookManager();
        $order = [];

        $cb1 = function () use (&$order) {
            $order[] = 'first';
        };

        $cb2 = function () use (&$order) {
            $order[] = 'second';
        };

        $manager->addAction('init', $cb1, priority: 10);
        $manager->addAction('init', $cb2, priority: 10);
        $manager->removeAction('init', $cb1, priority: 10);
        $manager->doAction('init');

        $this->assertSame(['second'], $order);
    }

    #[Test]
    public function it_returns_false_when_removing_unknown_action(): void
    {
        $manager = new HookManager();

        $this->assertFalse($manager->removeAction('init', fn () => ''));
    }

    #[Test]
    public function it_reports_has_action_correctly(): void
    {
        $manager = new HookManager();

        $this->assertFalse($manager->hasAction('init'));

        $manager->addAction('init', fn () => '');

        $this->assertTrue($manager->hasAction('init'));
    }
}
