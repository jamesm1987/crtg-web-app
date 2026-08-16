<?php

namespace App\Filament\Resources\Competitions;

use App\Filament\Resources\Competitions\Pages\{CreateCompetition, EditCompetition, ListCompetitions, ViewCompetition};
use App\Filament\Resources\Competitions\Schemas\CompetitionForm;
use App\Filament\Resources\Competitions\Tables\CompetitionsTable;
use App\Filament\Resources\Competitions\RelationManagers\TeamsRelationManager;
use App\Filament\Resources\Competitions\RelationManagers\FixturesRelationManager;
use App\Filament\Resources\Competitions\Resources\Teams\TeamResource;
use App\Models\Competition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CompetitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetitionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
            FixturesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCompetitions::route('/'),
            'create' => CreateCompetition::route('/create'),
            'view'   => ViewCompetition::route('/{record}'),
            'edit'   => EditCompetition::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            LeagueStandingsWidget::class,
        ];
    }
}
