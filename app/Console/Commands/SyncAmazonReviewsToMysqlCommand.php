<?php

namespace App\Console\Commands;

use App\Models\AmazonReview as ClickHouseAmazonReview;
use App\Models\Mysql\AmazonReview as MysqlAmazonReview;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncAmazonReviewsToMysqlCommand extends Command
{
    protected $signature = 'amazon-reviews:sync-mysql
                            {--chunk=500 : Rows read from ClickHouse per batch}
                            {--truncate : Empty MySQL table before sync}';

    protected $description = 'Copy amazon_reviews from ClickHouse into MySQL for performance comparison';

    public function handle(): int
    {
        $chunkSize = max(50, (int) $this->option('chunk'));

        // Large review bodies can exceed wait timeouts on slow local inserts.
        try {
            DB::connection('mysql')->statement('SET GLOBAL max_allowed_packet = 67108864');
        } catch (\Throwable) {
            // May lack SUPER privilege — fall back to small insert batches below.
        }

        DB::connection('mysql')->statement('SET SESSION wait_timeout = 600');
        DB::connection('mysql')->statement('SET SESSION net_read_timeout = 600');
        DB::connection('mysql')->statement('SET SESSION net_write_timeout = 600');

        if ($this->option('truncate')) {
            $this->warn('Truncating MySQL amazon_reviews…');
            DB::connection('mysql')->table('amazon_reviews')->truncate();
        }

        $total = (int) ClickHouseAmazonReview::query()->count();

        if ($total === 0) {
            $this->error('ClickHouse amazon_reviews is empty. Seed it first.');

            return self::FAILURE;
        }

        $this->info(sprintf('Syncing %s rows from ClickHouse → MySQL (chunk=%s)…', number_format($total), number_format($chunkSize)));

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $synced = 0;

        ClickHouseAmazonReview::query()
            ->orderBy('review_date')
            ->orderBy('review_id')
            ->chunk($chunkSize, function ($rows) use (&$synced, $bar, $chunkSize): void {
                $payload = $rows->map(function ($row): array {
                    return [
                        'review_date' => $row->review_date,
                        'marketplace' => $row->marketplace,
                        'customer_id' => $row->customer_id,
                        'review_id' => $row->review_id,
                        'product_id' => $row->product_id,
                        'product_parent' => $row->product_parent,
                        'product_title' => $row->product_title,
                        'product_category' => $row->product_category,
                        'star_rating' => $row->star_rating,
                        'helpful_votes' => $row->helpful_votes,
                        'total_votes' => $row->total_votes,
                        'vine' => (bool) $row->vine ? 1 : 0,
                        'verified_purchase' => (bool) $row->verified_purchase ? 1 : 0,
                        'review_headline' => $row->review_headline,
                        'review_body' => $row->review_body,
                    ];
                })->all();

                foreach (array_chunk($payload, min(100, $chunkSize)) as $batch) {
                    try {
                        DB::connection('mysql')->table('amazon_reviews')->upsert(
                            $batch,
                            ['review_id'],
                            [
                                'review_date',
                                'marketplace',
                                'customer_id',
                                'product_id',
                                'product_parent',
                                'product_title',
                                'product_category',
                                'star_rating',
                                'helpful_votes',
                                'total_votes',
                                'vine',
                                'verified_purchase',
                                'review_headline',
                                'review_body',
                            ]
                        );
                    } catch (\Throwable) {
                        DB::purge('mysql');
                        DB::reconnect('mysql');

                        try {
                            DB::connection('mysql')->statement('SET GLOBAL max_allowed_packet = 67108864');
                        } catch (\Throwable) {
                            // ignore
                        }

                        // Last resort: insert row-by-row for this batch.
                        foreach ($batch as $row) {
                            DB::connection('mysql')->table('amazon_reviews')->upsert(
                                [$row],
                                ['review_id'],
                                [
                                    'review_date',
                                    'marketplace',
                                    'customer_id',
                                    'product_id',
                                    'product_parent',
                                    'product_title',
                                    'product_category',
                                    'star_rating',
                                    'helpful_votes',
                                    'total_votes',
                                    'vine',
                                    'verified_purchase',
                                    'review_headline',
                                    'review_body',
                                ]
                            );
                        }
                    }
                }

                $synced += count($payload);
                $bar->advance(count($payload));
            });

        $bar->finish();
        $this->newLine(2);

        $mysqlCount = MysqlAmazonReview::query()->count();
        $this->info(sprintf('Done. Synced ~%s rows. MySQL now has %s rows.', number_format($synced), number_format($mysqlCount)));

        return self::SUCCESS;
    }
}
