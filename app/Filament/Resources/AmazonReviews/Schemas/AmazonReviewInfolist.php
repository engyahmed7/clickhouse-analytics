<?php

namespace App\Filament\Resources\AmazonReviews\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AmazonReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('review_id')
                            ->label('Review ID')
                            ->copyable(),
                        TextEntry::make('review_date')
                            ->date(),
                        TextEntry::make('star_rating')
                            ->label('Stars'),
                        IconEntry::make('verified_purchase')
                            ->boolean(),
                        IconEntry::make('vine')
                            ->boolean(),
                        TextEntry::make('marketplace'),
                        TextEntry::make('review_headline')
                            ->columnSpanFull(),
                        TextEntry::make('review_body')
                            ->columnSpanFull()
                            ->prose(),
                    ]),
                Section::make('Product')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('product_id')
                            ->copyable(),
                        TextEntry::make('product_category')
                            ->badge(),
                        TextEntry::make('product_title')
                            ->columnSpanFull(),
                        TextEntry::make('product_parent'),
                        TextEntry::make('customer_id'),
                        TextEntry::make('helpful_votes')
                            ->numeric(),
                        TextEntry::make('total_votes')
                            ->numeric(),
                    ]),
            ]);
    }
}
