<?php

use ClickHouse\Laravel\Schema\Blueprint as ClickHouseBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'clickhouse';

    public function up(): void
    {
        Schema::connection('clickhouse')->create('events', function (ClickHouseBlueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedInteger('user_id');
            $table->text('type');
            $table->text('name')->nullable();
            $table->dateTime('created_at');

            $table->engine('MergeTree()');
            $table->orderBy(['id']);
            $table->partitionBy('toYYYYMM(created_at)');
        });
    }

    public function down(): void
    {
        Schema::connection('clickhouse')->drop('events');
    }
};
