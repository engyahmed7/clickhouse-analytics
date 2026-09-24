<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('mysql')->hasTable('amazon_reviews')) {
            return;
        }

        Schema::connection('mysql')->create('amazon_reviews', function (Blueprint $table) {
            $table->date('review_date');
            $table->string('marketplace', 16)->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->string('review_id', 64)->primary();
            $table->string('product_id', 64)->index();
            $table->unsignedBigInteger('product_parent');
            $table->text('product_title');
            $table->string('product_category', 64)->index();
            $table->unsignedTinyInteger('star_rating')->index();
            $table->unsignedInteger('helpful_votes')->default(0);
            $table->unsignedInteger('total_votes')->default(0);
            $table->boolean('vine')->default(false);
            $table->boolean('verified_purchase')->default(false)->index();
            $table->text('review_headline');
            $table->mediumText('review_body');

            $table->index(['product_category', 'review_date']);
            $table->index(['review_date', 'product_category']);
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('amazon_reviews');
    }
};
