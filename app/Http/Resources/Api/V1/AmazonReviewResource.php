<?php

namespace App\Http\Resources\Api\V1;

use App\Models\AmazonReview;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AmazonReview
 */
class AmazonReviewResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'review_id' => $this->review_id,
            'review_date' => $this->review_date,
            'marketplace' => $this->marketplace,
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'product_parent' => $this->product_parent,
            'product_title' => $this->product_title,
            'product_category' => $this->product_category,
            'star_rating' => $this->star_rating,
            'helpful_votes' => $this->helpful_votes,
            'total_votes' => $this->total_votes,
            'vine' => (bool) $this->vine,
            'verified_purchase' => (bool) $this->verified_purchase,
            'review_headline' => $this->review_headline,
            'review_body' => $this->review_body,
        ];
    }
}
