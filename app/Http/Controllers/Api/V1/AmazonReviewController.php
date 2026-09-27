<?php

namespace App\Http\Controllers\Api\V1;

use App\Analytics\Contracts\AnalyticsSourceFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListAmazonReviewsRequest;
use App\Http\Resources\Api\V1\AmazonReviewResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AmazonReviewController extends Controller
{
    public function __construct(
        private AnalyticsSourceFactory $analyticsSources,
    ) {}

    public function index(ListAmazonReviewsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();
        $source = $filters['source'] ?? $this->analyticsSources->default();

        ini_set('memory_limit', '512M');

        /** @var class-string<Model> $modelClass */
        $modelClass = $this->analyticsSources->make($source);

        $reviews = $this->filteredReviews($modelClass, $filters)->get();

        return AmazonReviewResource::collection($reviews);
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
