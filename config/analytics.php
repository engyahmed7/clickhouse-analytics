<?php

use App\Models\AmazonReview;
use App\Models\Mysql\AmazonReview as MysqlAmazonReview;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Analytics Source
    |--------------------------------------------------------------------------
    |
    | Used when the API (or other callers) omit ?source=. Must match a key in
    | the "sources" array below.
    |
    */

    'default' => env('ANALYTICS_DEFAULT_SOURCE', 'clickhouse'),

    /*
    |--------------------------------------------------------------------------
    | Analytics Sources Registry
    |--------------------------------------------------------------------------
    |
    | Each source is a named product concept ("where do we read analytics?").
    | Physical DB credentials live in config/database.php connections.
    |
    | To add another warehouse later:
    | 1. Add a connection in config/database.php
    | 2. Create an Eloquent model bound to that connection
    | 3. Register it here — no controller changes required
    |
    */

    'sources' => [

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

    ],

];
