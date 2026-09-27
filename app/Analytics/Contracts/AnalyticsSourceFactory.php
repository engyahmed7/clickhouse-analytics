<?php

namespace App\Analytics\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AnalyticsSourceFactory
{
    /**
     * @return class-string<Model>
     */
    public function model(string $source): string;

    public function connection(string $source): string;

    public function label(string $source): string;

    public function default(): string;

    public function has(string $source): bool;

    /**
     * @return list<string>
     */
    public function keys(): array;

    /**
     * @return array<string, array{label: string, connection: string, model: class-string<Model>}>
     */
    public function all(): array;
}
