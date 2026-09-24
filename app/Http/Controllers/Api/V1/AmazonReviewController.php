<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListAmazonReviewsRequest;
use App\Http\Resources\Api\V1\AmazonReviewResource;
use App\Models\AmazonReview as ClickHouseAmazonReview;
use App\Models\Mysql\AmazonReview as MysqlAmazonReview;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AmazonReviewController extends Controller
{
    public function index(ListAmazonReviewsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        ini_set('memory_limit', '512M');

        $reviews = $this->filteredReviews(
            $this->modelFor($filters['source'] ?? 'clickhouse'),
            $filters
        )->get();

        return AmazonReviewResource::collection($reviews);
    }

    /**
     * @return class-string<Model>
     */
    private function modelFor(string $source): string
    {
        return $source === 'mysql'
            ? MysqlAmazonReview::class
            : ClickHouseAmazonReview::class;
    }

    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<string, mixed>  $filters
     * @return Builder<Model>
     */
    private function filteredReviews(string $modelClass, array $filters): Builder
    {
        return $modelClass::query()
            ->when(
                $filters['product_category'] ?? null,
                fn (Builder $query, string $category) => $query->where('product_category', $category)
            )
            ->orderBy('review_date', 'desc');
    }
}
