<?php

namespace App\Models\Mysql;

use Illuminate\Database\Eloquent\Model;

class AmazonReview extends Model
{
    protected $connection = 'mysql';

    protected $table = 'amazon_reviews';

    protected $primaryKey = 'review_id';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'review_date' => 'date:Y-m-d',
            'vine' => 'boolean',
            'verified_purchase' => 'boolean',
            'star_rating' => 'integer',
            'helpful_votes' => 'integer',
            'total_votes' => 'integer',
            'customer_id' => 'integer',
            'product_parent' => 'integer',
        ];
    }
}
