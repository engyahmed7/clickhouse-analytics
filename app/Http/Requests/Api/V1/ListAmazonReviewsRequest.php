<?php

namespace App\Http\Requests\Api\V1;

use App\Analytics\Contracts\AnalyticsSourceFactory;
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
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var AnalyticsSourceFactory $sources */
        $sources = $this->container->make(AnalyticsSourceFactory::class);

        return [
            'source' => ['sometimes', 'string', Rule::in($sources->keys())],
            'all' => ['sometimes', 'boolean'],
            'product_category' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
