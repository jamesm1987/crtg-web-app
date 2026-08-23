<?php

namespace App\Filament\Resources\Competitions\Resources\TopScorers;

use App\Filament\Resources\Competitions\CompetitionResource;
use App\Filament\Resources\Competitions\Resources\TopScorers\Pages\CreateTopScorer;
use App\Filament\Resources\Competitions\Resources\TopScorers\Pages\EditTopScorer;
use App\Filament\Resources\Competitions\Resources\TopScorers\Schemas\TopScorerForm;
use App\Filament\Resources\Competitions\Resources\TopScorers\Tables\TopScorersTable;
use App\Models\TopScorer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TopScorerResource extends Resource
{
    protected static ?string $model = TopScorer::class;

    protected static string|BackedEnum|null $navigationIcon = 'team-badge';

    protected static ?string $parentResource = CompetitionResource::class;

    public static function form(Schema $schema): Schema
    {
        return TopScorerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TopScorersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateTopScorer::route('/create'),
            'edit' => EditTopScorer::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes();
    }
}
