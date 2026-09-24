<?php

namespace Tests\Feature;

use Tests\TestCase;

class AmazonReviewApiTest extends TestCase
{
    public function test_index_rejects_invalid_star_rating(): void
    {
        $response = $this->getJson('/api/v1/amazon-reviews?star_rating=9');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['star_rating']);
    }

    public function test_index_rejects_invalid_sort_column(): void
    {
        $response = $this->getJson('/api/v1/amazon-reviews?sort=not_a_column');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sort']);
    }

    public function test_index_returns_paginated_reviews(): void
    {
        $response = $this->getJson('/api/v1/amazon-reviews?per_page=5');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'review_id',
                        'review_date',
                        'marketplace',
                        'product_id',
                        'product_title',
                        'product_category',
                        'star_rating',
                        'helpful_votes',
                        'verified_purchase',
                        'review_headline',
                        'review_body',
                    ],
                ],
                'links',
                'meta',
            ]);

        $this->assertLessThanOrEqual(5, count($response->json('data')));
    }

    public function test_index_returns_all_matching_rows_when_all_flag_is_set(): void
    {
        $response = $this->getJson('/api/v1/amazon-reviews?all=1&star_rating=5&limit=25');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'review_id',
                        'star_rating',
                        'product_category',
                    ],
                ],
            ])
            ->assertJsonMissingPath('meta.current_page');

        $this->assertLessThanOrEqual(25, count($response->json('data')));

        foreach ($response->json('data') as $review) {
            $this->assertSame(5, (int) $review['star_rating']);
        }
    }

    public function test_show_returns_not_found_for_missing_review(): void
    {
        $response = $this->getJson('/api/v1/amazon-reviews/DOES-NOT-EXIST');

        $response->assertNotFound();
    }
}
