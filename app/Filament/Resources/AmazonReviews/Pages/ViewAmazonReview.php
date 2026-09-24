<?php

namespace App\Filament\Resources\AmazonReviews\Pages;

use App\Filament\Resources\AmazonReviews\AmazonReviewResource;
use Filament\Resources\Pages\ViewRecord;

class ViewAmazonReview extends ViewRecord
{
    protected static string $resource = AmazonReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
