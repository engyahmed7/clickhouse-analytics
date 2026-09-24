<?php

namespace App\Models;

use ClickHouse\Laravel\Eloquent\Model;

class AmazonReview extends Model
{
    protected $connection = 'clickhouse';

    protected $table = 'amazon_reviews';

    protected $primaryKey = 'review_id';

    protected $keyType = 'string';

    protected $guarded = [];

    public $timestamps = false;
}
