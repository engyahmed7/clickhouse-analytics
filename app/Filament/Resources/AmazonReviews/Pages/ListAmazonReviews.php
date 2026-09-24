<?php

namespace App\Filament\Resources\AmazonReviews\Pages;

use App\Filament\Resources\AmazonReviews\AmazonReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListAmazonReviews extends ListRecords
{
    protected static string $resource = AmazonReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
