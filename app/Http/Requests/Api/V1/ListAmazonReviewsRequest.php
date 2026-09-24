<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListAmazonReviewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('all')) {
            $this->merge([
                'all' => filter_var($this->input('all'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }

        if ($this->has('verified_purchase')) {
            $this->merge([
                'verified_purchase' => filter_var(
                    $this->input('verified_purchase'),
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                ),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'all' => ['sometimes', 'boolean'],
            'product_category' => ['sometimes', 'string', 'max:255'],
            'product_id' => ['sometimes', 'string', 'max:255'],
            'marketplace' => ['sometimes', 'string', 'max:16'],
            'star_rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'verified_purchase' => ['sometimes', 'boolean'],
            'min_helpful_votes' => ['sometimes', 'integer', 'min:0'],
            'search' => ['sometimes', 'string', 'max:255'],
            'sort' => ['sometimes', 'string', Rule::in([
                'review_date',
                'star_rating',
                'helpful_votes',
                'total_votes',
            ])],
            'direction' => ['sometimes', 'string', Rule::in(['asc', 'desc'])],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:1000'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:500000'],
        ];
    }
}
