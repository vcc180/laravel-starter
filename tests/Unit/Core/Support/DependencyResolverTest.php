<?php

namespace Tests\Unit\Core\Support;

use Core\Exceptions\CoreException;
use Core\Support\DependencyResolver;
use PHPUnit\Framework\TestCase;

class DependencyResolverTest extends TestCase
{
    private DependencyResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new DependencyResolver();
    }

    public function test_resolve_simple_ordered_packages(): void
    {
        $order = $this->resolver->resolve([
            'users' => ['slug' => 'users', 'version' => '1.0', 'requires' => []],
            'blog' => ['slug' => 'blog', 'version' => '1.0', 'requires' => ['users' => '^1.0']],
        ]);

        $this->assertSame(['users', 'blog'], $order);
    }

    public function test_resolve_respects_full_dependency_chain(): void
    {
        $order = $this->resolver->resolve([
            'shop' => ['slug' => 'shop', 'version' => '1.0', 'requires' => ['blog' => '^1.0']],
            'users' => ['slug' => 'users', 'version' => '1.0', 'requires' => []],
            'blog' => ['slug' => 'blog', 'version' => '1.0', 'requires' => ['users' => '^1.0']],
        ]);

        $this->assertSame(['users', 'blog', 'shop'], $order);
    }

    public function test_resolve_detects_circular_dependency(): void
    {
        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('Circular dependency detected: blog');

        $this->resolver->resolve([
            'blog' => ['slug' => 'blog', 'version' => '1.0', 'requires' => ['users' => '^1.0']],
            'users' => ['slug' => 'users', 'version' => '1.0', 'requires' => ['blog' => '^1.0']],
        ]);
    }

    public function test_resolve_detects_missing_dependency_seed(): void
    {
        $this->expectException(CoreException::class);

        $this->resolver->resolve([
            'users' => ['slug' => 'users', 'version' => '1.0', 'requires' => ['missing' => '^1.0']],
        ]);
    }

    public function test_resolve_handles_empty_package_list(): void
    {
        $this->assertSame([], $this->resolver->resolve([]));
    }
}
