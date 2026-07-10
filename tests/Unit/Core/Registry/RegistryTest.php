<?php

namespace Tests\Unit\Core\Registry;

use Core\Contracts\RegistryInterface;
use Core\Registry\Registry;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
    private RegistryInterface $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new Registry();
    }

    public function test_register_and_get_returns_value(): void
    {
        $this->registry->register('modules', 'blog', ['path' => '/modules/blog']);

        $this->assertSame(['path' => '/modules/blog'], $this->registry->get('modules', 'blog'));
        $this->assertTrue($this->registry->has('modules', 'blog'));
    }

    public function test_get_returns_default_when_missing(): void
    {
        $this->assertNull($this->registry->get('modules', 'missing'));
        $this->assertSame('fallback', $this->registry->get('modules', 'missing', 'fallback'));
    }

    public function test_remove_and_has_after_remove(): void
    {
        $this->registry->register('modules', 'blog', '/modules/blog');
        $this->registry->remove('modules', 'blog');

        $this->assertFalse($this->registry->has('modules', 'blog'));
    }

    public function test_clear_type_removes_only_matching_type(): void
    {
        $this->registry->register('modules', 'blog', '/modules/blog');
        $this->registry->register('plugins', 'seo', '/plugins/seo');

        $this->registry->clear('modules');

        $this->assertFalse($this->registry->has('modules', 'blog'));
        $this->assertTrue($this->registry->has('plugins', 'seo'));
    }

    public function test_clear_empty_type_clears_all(): void
    {
        $this->registry->register('modules', 'blog', '/modules/blog');
        $this->registry->register('plugins', 'seo', '/plugins/seo');

        $this->registry->clear('');

        $this->assertFalse($this->registry->has('modules', 'blog'));
        $this->assertFalse($this->registry->has('plugins', 'seo'));
    }

    public function test_all_returns_only_matching_type_values(): void
    {
        $this->registry->register('modules', 'blog', '/modules/blog');
        $this->registry->register('modules', 'shop', '/modules/shop');
        $this->registry->register('plugins', 'seo', '/plugins/seo');

        $modules = $this->registry->all('modules');

        $this->assertCount(2, $modules);
        $this->assertContains('/modules/blog', $modules);
        $this->assertContains('/modules/shop', $modules);
    }

    public function test_overwrite_existing_key(): void
    {
        $this->registry->register('modules', 'blog', '/old');
        $this->registry->register('modules', 'blog', '/new');

        $this->assertSame('/new', $this->registry->get('modules', 'blog'));
    }
}
