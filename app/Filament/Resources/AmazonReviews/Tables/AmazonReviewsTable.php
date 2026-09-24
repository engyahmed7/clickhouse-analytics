<?php

namespace App\Filament\Resources\AmazonReviews\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AmazonReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('review_id')
                    ->label('Review ID')
                    ->searchable()
                    ->limit(20)
                    ->copyable(),
                TextColumn::make('review_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('product_category')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product_title')
                    ->limit(40)
                    ->searchable()
                    ->wrap(),
                TextColumn::make('star_rating')
                    ->label('Stars')
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('verified_purchase')
                    ->boolean(),
                TextColumn::make('helpful_votes')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('marketplace')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('review_date', 'desc')
            ->filters([
                SelectFilter::make('product_category')
                    ->options([
                        'Grocery' => 'Grocery',
                        'Wireless' => 'Wireless',
                        'Apparel' => 'Apparel',
                        'Camera' => 'Camera',
                        'Books' => 'Books',
                        'Electronics' => 'Electronics',
                        'Home' => 'Home',
                        'Sports' => 'Sports',
                        'Toys' => 'Toys',
                        'Beauty' => 'Beauty',
                    ])
                    ->searchable(),
                SelectFilter::make('star_rating')
                    ->options([
                        1 => '1 star',
                        2 => '2 stars',
                        3 => '3 stars',
                        4 => '4 stars',
                        5 => '5 stars',
                    ]),
                SelectFilter::make('verified_purchase')
                    ->options([
                        1 => 'Verified',
                        0 => 'Not verified',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
