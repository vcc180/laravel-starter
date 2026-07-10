<?php

namespace Core\Support;

use Core\Contracts\ResultInterface;

final class Result implements ResultInterface
{
    private bool $ok;

    private string $message;

    /**
     * @var array<string, mixed>
     */
    private array $data;

    private int $code;

    /**
     * @param array<string, mixed> $data
     */
    private function __construct(bool $ok, string $message, array $data, int $code)
    {
        $this->ok = $ok;
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;
    }

    public static function ok(array $data = []): self
    {
        return new self(true, '', $data, 0);
    }

    public static function fail(string $message, array $data = [], int $code = 0): self
    {
        return new self(false, $message, $data, $code);
    }

    public function isOk(): bool
    {
        return $this->ok;
    }

    public function message(): string
    {
        return $this->message;
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }

    public function code(): int
    {
        return $this->code;
    }
}
