<?php

namespace App\Filament\Resources\UserTeamPicks;
use App\Filament\Resources\UserTeamPicks\Pages\ListUserTeamPicks;
use App\Filament\Resources\UserTeamPicks\Pages\ViewUserTeamPick;
use App\Filament\Resources\UserTeamPicks\Schemas\UserTeamPickForm;
use App\Filament\Resources\UserTeamPicks\Tables\UserTeamPicksTable;
use App\Models\UserTeamPick;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserTeamPickResource extends Resource
{
    protected static ?string $model = UserTeamPick::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Team Picks';

    protected static ?string $modelLabel = 'Team Pick';

    protected static ?string $pluralModelLabel = 'Team Picks';

    public static function form(Schema $schema): Schema 

    {
        return UserTeamPickForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserTeamPicksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserTeamPicks::route('/'),
            'view'  => ViewUserTeamPick::route('/{record}'),
        ];
    }
}
