<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EntryPoint\Api\Response;

interface ApiResponse
{
    public function data(): mixed;
    public function status(): int;
    public function message(): string;
    public function errors(): array;
    public function metadata(): array;
    public function headers(): array;
}
