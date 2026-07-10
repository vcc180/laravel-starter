<?php

namespace Tests\Unit\Core;

use Core\Contracts\ResultInterface;
use Core\Support\Result;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ManifestTest extends TestCase
{
    public function test_constructs_and_requires_ok_with_valid_manifest(): void
    {
        $path = sys_get_temp_dir().'/valid-manifest.json';
        file_put_contents($path, json_encode([
            'name' => 'Blog',
            'slug' => 'blog',
            'version' => '1.0.0',
            'type' => 'module',
            'provider' => 'Modules\Blog\Providers\BlogServiceProvider',
        ]));

        try {
            $manifest = new \Core\Support\Manifest($path);
            $manifest->require(['name', 'slug', 'version', 'type']);

            $this->assertSame('blog', $manifest->get('slug'));
            $this->assertSame('1.0.0', $manifest->get('version'));
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function test_throws_on_missing_file(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new \Core\Support\Manifest(__DIR__.'/does_not_exist.json');
    }

    public function test_throws_on_invalid_json(): void
    {
        $path = sys_get_temp_dir().'/invalid-manifest.json';
        file_put_contents($path, '{invalid_json}');

        $this->expectException(InvalidArgumentException::class);
        new \Core\Support\Manifest($path);

        unlink($path);
    }

    public function test_throws_on_missing_required_keys(): void
    {
        $path = sys_get_temp_dir().'/missing-keys.json';
        file_put_contents($path, json_encode(['name' => 'Test']));

        $this->expectException(InvalidArgumentException::class);

        try {
            $manifest = new \Core\Support\Manifest($path);
            $manifest->require(['slug', 'version', 'type']);
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}

class ResultTest extends TestCase
{
    public function test_ok_returns_success_result(): void
    {
        $result = Result::ok(['id' => 1]);

        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertTrue($result->isOk());
        $this->assertSame('', $result->message());
        $this->assertSame(['id' => 1], $result->data());
        $this->assertSame(0, $result->code());
    }

    public function test_fail_returns_error_result(): void
    {
        $result = Result::fail('Something went wrong', ['code' => 'E001'], 400);

        $this->assertFalse($result->isOk());
        $this->assertSame('Something went wrong', $result->message());
        $this->assertSame(['code' => 'E001'], $result->data());
        $this->assertSame(400, $result->code());
    }
}
