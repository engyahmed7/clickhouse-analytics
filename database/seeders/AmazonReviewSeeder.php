<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmazonReviewSeeder extends Seeder
{
    /**
     * Public Amazon reviews Parquet sample on S3 (ClickHouse docs dataset).
     *
     * @see https://clickhouse.com/docs/getting-started/example-datasets/amazon-reviews
     */
    private const S3_URL = 'https://datasets-documentation.s3.eu-west-3.amazonaws.com/amazon_reviews/amazon_reviews_2015.snappy.parquet';

    public function run(): void
    {
        $limit = (int) env('AMAZON_REVIEWS_SEED_LIMIT', 100_000);

        $this->command?->info($limit > 0
            ? "Loading up to {$limit} Amazon reviews from S3 into ClickHouse…"
            : 'Loading full Amazon reviews file from S3 (this can take several minutes)…');

        config(['database.connections.clickhouse.timeout' => (float) env('AMAZON_REVIEWS_SEED_TIMEOUT', 600)]);
        DB::purge('clickhouse');

        $from = sprintf("s3('%s', NOSIGN)", self::S3_URL);

        $sql = $limit > 0
            ? "INSERT INTO amazon_reviews SELECT * FROM {$from} LIMIT {$limit}"
            : "INSERT INTO amazon_reviews SELECT * FROM {$from}";

        DB::connection('clickhouse')->statement($sql);

        $count = DB::connection('clickhouse')->table('amazon_reviews')->count();

        $this->command?->info("Done. amazon_reviews now has {$count} rows.");
    }
}
