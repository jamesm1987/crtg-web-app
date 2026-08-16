<?php

namespace App\Filament\Resources\TeamPointsLedgers;

use App\Filament\Resources\Fixtures\FixtureResource;
use App\Filament\Resources\TeamPointsLedgers\Pages\ListTeamPointsLedgers;
use App\Filament\Resources\TeamPointsLedgers\Schemas\TeamPointsLedgerForm;
use App\Filament\Resources\TeamPointsLedgers\Tables\TeamPointsLedgersTable;
use App\Models\TeamPointsLedger;
use BackedEnum;
use UnitEnum;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeamPointsLedgerResource extends Resource
{
    protected static ?string $model = TeamPointsLedger::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Points';

    protected static ?string $navigationLabel = 'Points log';

    protected static ?string $modelLabel = 'Point Log';

    protected static ?string $pluralModelLabel = 'Points Log';

    public static function form(Schema $schema): Schema
    {
        return TeamPointsLedgerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamPointsLedgersTable::configure($table);
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
            'index' => ListTeamPointsLedgers::route('/'),
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

    public static function canDelete($record): bool
    {
        return false;
    }
}
