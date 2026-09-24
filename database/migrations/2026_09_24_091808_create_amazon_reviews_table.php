<?php

use ClickHouse\Laravel\Schema\Blueprint as ClickHouseBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'clickhouse';

    public function up(): void
    {
        Schema::connection('clickhouse')->create('amazon_reviews', function (ClickHouseBlueprint $table) {
            $table->date('review_date');
            $table->text('marketplace')->lowCardinality();
            $table->unsignedBigInteger('customer_id');
            $table->text('review_id');
            $table->text('product_id');
            $table->unsignedBigInteger('product_parent');
            $table->text('product_title');
            $table->text('product_category')->lowCardinality();
            $table->unsignedTinyInteger('star_rating');
            $table->unsignedInteger('helpful_votes');
            $table->unsignedInteger('total_votes');
            $table->boolean('vine');
            $table->boolean('verified_purchase');
            $table->text('review_headline');
            $table->text('review_body');

            $table->engine('MergeTree()');
            $table->orderBy(['review_date', 'product_category']);
            $table->partitionBy('toYYYYMM(review_date)');
        });
    }

    public function down(): void
    {
        Schema::connection('clickhouse')->drop('amazon_reviews');
    }
};
