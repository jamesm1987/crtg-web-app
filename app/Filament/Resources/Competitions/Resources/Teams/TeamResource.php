<?php

namespace App\Filament\Resources\Competitions\Resources\Teams;

use App\Filament\Resources\Competitions\CompetitionResource;
use App\Filament\Resources\Competitions\Resources\Teams\Pages\CreateTeam;
use App\Filament\Resources\Competitions\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Competitions\Resources\Teams\Schemas\TeamForm;
use App\Filament\Resources\Competitions\Resources\Teams\Tables\TeamsTable;
use App\Models\Team;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = 'team-badge';

    protected static ?string $parentResource = CompetitionResource::class;

    public static function form(Schema $schema): Schema
    {
        return TeamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamsTable::configure($table);
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
            'create' => CreateTeam::route('/create'),
            'edit' => EditTeam::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
