<?php

namespace Tests\Unit;

use App\Analytics\AnalyticsSourceManager;
use App\Analytics\Exceptions\UnknownAnalyticsSourceException;
use App\Models\AmazonReview;
use App\Models\Mysql\AmazonReview as MysqlAmazonReview;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AnalyticsSourceManagerTest extends TestCase
{
    private AnalyticsSourceManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('analytics.default', 'clickhouse');
        Config::set('analytics.sources', [
            'clickhouse' => [
                'label' => 'ClickHouse',
                'connection' => 'clickhouse',
                'model' => AmazonReview::class,
            ],
            'mysql' => [
                'label' => 'MySQL',
                'connection' => 'mysql',
                'model' => MysqlAmazonReview::class,
            ],
        ]);

        $this->manager = new AnalyticsSourceManager;
    }

    public function test_make_returns_clickhouse_model(): void
    {
        $this->assertSame(AmazonReview::class, $this->manager->make('clickhouse'));
    }

    public function test_make_returns_mysql_model(): void
    {
        $this->assertSame(MysqlAmazonReview::class, $this->manager->make('mysql'));
    }

    public function test_keys_lists_registered_sources(): void
    {
        $this->assertSame(['clickhouse', 'mysql'], $this->manager->keys());
    }

    public function test_default_source_is_clickhouse(): void
    {
        $this->assertSame('clickhouse', $this->manager->default());
    }

    public function test_unknown_source_throws(): void
    {
        $this->expectException(UnknownAnalyticsSourceException::class);

        $this->manager->make('postgres');
    }

    public function test_adding_a_source_in_config_is_picked_up(): void
    {
        Config::set('analytics.sources.postgres', [
            'label' => 'PostgreSQL',
            'connection' => 'pgsql',
            'model' => MysqlAmazonReview::class,
        ]);

        $this->assertTrue($this->manager->has('postgres'));
        $this->assertSame(MysqlAmazonReview::class, $this->manager->make('postgres'));
        $this->assertContains('postgres', $this->manager->keys());
    }
}
