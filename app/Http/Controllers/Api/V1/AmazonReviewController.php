<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListAmazonReviewsRequest;
use App\Http\Resources\Api\V1\AmazonReviewResource;
use App\Models\AmazonReview;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AmazonReviewController extends Controller
{
    public function index(ListAmazonReviewsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();
        $query = $this->filteredReviews($filters);

        if ($filters['all'] ?? false) {
            // Large result sets need more memory than the default PHP CLI/FPM limit.
            ini_set('memory_limit', '512M');

            if (isset($filters['limit'])) {
                $query->limit($filters['limit']);
            }

            return AmazonReviewResource::collection($query->get());
        }

        return AmazonReviewResource::collection(
            $query->paginate($filters['per_page'] ?? 20)
        );
    }

    public function show(string $reviewId): AmazonReviewResource
    {
        $review = AmazonReview::query()
            ->where('review_id', $reviewId)
            ->firstOrFail();

        return new AmazonReviewResource($review);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<AmazonReview>
     */
    private function filteredReviews(array $filters): Builder
    {
        return AmazonReview::query()
            ->when(
                $filters['product_category'] ?? null,
                fn (Builder $query, string $category) => $query->where('product_category', $category)
            )
            ->when(
                $filters['product_id'] ?? null,
                fn (Builder $query, string $productId) => $query->where('product_id', $productId)
            )
            ->when(
                $filters['marketplace'] ?? null,
                fn (Builder $query, string $marketplace) => $query->where('marketplace', $marketplace)
            )
            ->when(
                array_key_exists('star_rating', $filters),
                fn (Builder $query) => $query->where('star_rating', $filters['star_rating'])
            )
            ->when(
                array_key_exists('verified_purchase', $filters),
                fn (Builder $query) => $query->where('verified_purchase', $filters['verified_purchase'])
            )
            ->when(
                $filters['min_helpful_votes'] ?? null,
                fn (Builder $query, int $votes) => $query->where('helpful_votes', '>=', $votes)
            )
            ->when(
                $filters['search'] ?? null,
                function (Builder $query, string $search): void {
                    $query->where(function (Builder $query) use ($search): void {
                        $query->where('review_headline', 'like', "%{$search}%")
                            ->orWhere('review_body', 'like', "%{$search}%")
                            ->orWhere('product_title', 'like', "%{$search}%");
                    });
                }
            )
            ->orderBy(
                $filters['sort'] ?? 'review_date',
                $filters['direction'] ?? 'desc'
            );
    }
}
