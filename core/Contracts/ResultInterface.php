<?php

namespace Core\Contracts;

interface ResultInterface
{
    public static function ok(array $data = []): self;

    public static function fail(string $message, array $data = [], int $code = 0): self;

    public function isOk(): bool;

    public function message(): string;

    /**
     * @return array<string, mixed>
     */
    public function data(): array;

    public function code(): int;
}
