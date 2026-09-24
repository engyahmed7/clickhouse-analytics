<?php

namespace Tests\Feature;

use Tests\TestCase;

class AmazonReviewApiTest extends TestCase
{
    public function test_index_rejects_invalid_source(): void
    {
        $response = $this->getJson('/api/v1/amazon-reviews?source=postgres');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['source']);
    }

    public function test_index_returns_clickhouse_reviews_for_category(): void
    {
        $response = $this->getJson(
            '/api/v1/amazon-reviews?all=1&product_category=Grocery&source=clickhouse'
        );

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'review_id',
                        'product_category',
                        'star_rating',
                    ],
                ],
            ]);

        foreach ($response->json('data') as $review) {
            $this->assertSame('Grocery', $review['product_category']);
        }
    }

    public function test_index_returns_mysql_reviews_for_category(): void
    {
        $response = $this->getJson(
            '/api/v1/amazon-reviews?all=1&product_category=Grocery&source=mysql'
        );

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'review_id',
                        'product_category',
                    ],
                ],
            ]);

        foreach ($response->json('data') as $review) {
            $this->assertSame('Grocery', $review['product_category']);
        }
    }
}
