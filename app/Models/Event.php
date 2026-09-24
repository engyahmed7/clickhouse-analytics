<?php

namespace App\Models;

use ClickHouse\Laravel\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'clickhouse';

    protected $table = 'events';

    protected $guarded = [];
}
