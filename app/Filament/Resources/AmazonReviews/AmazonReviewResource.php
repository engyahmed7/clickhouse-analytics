<?php

namespace App\Filament\Resources\AmazonReviews;

use App\Filament\Resources\AmazonReviews\Pages\ListAmazonReviews;
use App\Filament\Resources\AmazonReviews\Pages\ViewAmazonReview;
use App\Filament\Resources\AmazonReviews\Schemas\AmazonReviewInfolist;
use App\Filament\Resources\AmazonReviews\Tables\AmazonReviewsTable;
use App\Models\AmazonReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AmazonReviewResource extends Resource
{
    protected static ?string $model = AmazonReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Analytics';

    protected static ?string $navigationLabel = 'Amazon Reviews';

    protected static ?string $modelLabel = 'Amazon review';

    protected static ?string $pluralModelLabel = 'Amazon reviews';

    protected static ?string $recordTitleAttribute = 'review_id';

    protected static ?int $navigationSort = 1;

    public static function infolist(Schema $schema): Schema
    {
        return AmazonReviewInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AmazonReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAmazonReviews::route('/'),
            'view' => ViewAmazonReview::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
