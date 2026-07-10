<?php

namespace Tests\Unit\Core\Discovery;

use Core\Discovery\Discovery;
use PHPUnit\Framework\TestCase;

class DiscoveryTest extends TestCase
{
    public function test_scan_returns_manifest_paths_by_type(): void
    {
        $root = sys_get_temp_dir().'/core-discovery-'.uniqid();
        mkdir($root.'/modules/blog', 0777, true);
        mkdir($root.'/plugins/seo', 0777, true);
        mkdir($root.'/themes/default', 0777, true);
        file_put_contents($root.'/modules/blog/module.json', '{}');
        file_put_contents($root.'/plugins/seo/plugin.json', '{}');
        file_put_contents($root.'/themes/default/theme.json', '{}');

        $discovery = new Discovery([
            'modules' => $root.'/modules',
            'plugins' => $root.'/plugins',
            'themes' => $root.'/themes',
        ]);

        $result = $discovery->scan();

        $this->assertCount(1, $result['modules']);
        $this->assertCount(1, $result['plugins']);
        $this->assertCount(1, $result['themes']);
        $this->assertStringContainsString('modules/blog/module.json', $result['modules'][0]);
        $this->assertStringContainsString('plugins/seo/plugin.json', $result['plugins'][0]);
        $this->assertStringContainsString('themes/default/theme.json', $result['themes'][0]);
    }

    public function test_scan_ignores_directories_without_manifest(): void
    {
        $root = sys_get_temp_dir().'/core-discovery-empty-'.uniqid();
        mkdir($root.'/modules/blog', 0777, true);
        mkdir($root.'/plugins/seo', 0777, true);
        file_put_contents($root.'/modules/blog/readme.md', 'readme');

        $discovery = new Discovery([
            'modules' => $root.'/modules',
            'plugins' => $root.'/plugins',
        ]);

        $result = $discovery->scan();

        $this->assertArrayHasKey('modules', $result);
        $this->assertSame([], $result['modules']);
        $this->assertArrayHasKey('plugins', $result);
        $this->assertSame([], $result['plugins']);
    }

    public function test_scan_overrides_directories_from_argument(): void
    {
        $root = sys_get_temp_dir().'/core-discovery-arg-'.uniqid();
        mkdir($root.'/modules/blog', 0777, true);
        mkdir($root.'/alt/blog', 0777, true);
        file_put_contents($root.'/modules/blog/module.json', '{}');
        file_put_contents($root.'/alt/blog/module.json', '{}');

        $discovery = new Discovery([
            'modules' => $root.'/modules',
        ]);

        $result = $discovery->scan(['modules' => $root.'/alt']);

        $this->assertCount(1, $result['modules']);
        $this->assertStringContainsString('alt/blog/module.json', $result['modules'][0]);
    }

    public function test_scan_handles_nonexistent_root(): void
    {
        $discovery = new Discovery(['missing' => sys_get_temp_dir().'/missing-'.uniqid()]);

        $result = $discovery->scan();

        $this->assertArrayHasKey('missing', $result);
        $this->assertSame([], $result['missing']);
    }
}
