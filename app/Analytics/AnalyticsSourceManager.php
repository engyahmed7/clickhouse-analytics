<?php

namespace App\Analytics;

use App\Analytics\Contracts\AnalyticsSourceFactory;
use App\Analytics\Exceptions\UnknownAnalyticsSourceException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AnalyticsSourceManager implements AnalyticsSourceFactory
{
    /**
     * @return class-string<Model>
     */
    public function model(string $source): string
    {
        /** @var class-string<Model> $model */
        $model = $this->source($source)['model'];

        return $model;
    }

    public function connection(string $source): string
    {
        return $this->source($source)['connection'];
    }

    public function label(string $source): string
    {
        return $this->source($source)['label'];
    }

    public function default(): string
    {
        $default = (string) Config::get('analytics.default', 'clickhouse');

        if (! $this->has($default)) {
            throw UnknownAnalyticsSourceException::for($default, $this->keys());
        }

        return $default;
    }

    public function has(string $source): bool
    {
        return array_key_exists($source, $this->all());
    }

    /**
     * @return list<string>
     */
    public function keys(): array
    {
        return array_keys($this->all());
    }

    /**
     * @return array<string, array{label: string, connection: string, model: class-string<Model>}>
     */
    public function all(): array
    {
        /** @var array<string, array{label: string, connection: string, model: class-string<Model>}> $sources */
        $sources = Config::get('analytics.sources', []);

        return $sources;
    }

    /**
     * Resolve a registered source by name (factory entry point).
     *
     * @return class-string<Model>
     */
    public function make(string $source): string
    {
        return $this->model($source);
    }

    /**
     * @return array{label: string, connection: string, model: class-string<Model>}
     */
    private function source(string $source): array
    {
        if (! $this->has($source)) {
            throw UnknownAnalyticsSourceException::for($source, $this->keys());
        }

        return $this->all()[$source];
    }
}
