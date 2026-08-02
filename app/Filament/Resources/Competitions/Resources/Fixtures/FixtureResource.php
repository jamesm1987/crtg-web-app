<?php

namespace App\Filament\Resources\Competitions\Resources\Fixtures;

use App\Filament\Resources\Competitions\CompetitionResource;
use App\Filament\Resources\Competitions\Resources\Fixtures\Pages\CreateFixture;
use App\Filament\Resources\Competitions\Resources\Fixtures\Pages\EditFixture;
use App\Filament\Resources\Competitions\Resources\Fixtures\Schemas\FixtureForm;
use App\Filament\Resources\Competitions\Resources\Fixtures\Tables\FixturesTable;
use App\Models\Fixture;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FixtureResource extends Resource
{
    protected static ?string $model = Fixture::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = CompetitionResource::class;

    public static function form(Schema $schema): Schema
    {
        return FixtureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FixturesTable::configure($table);
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
            'create' => CreateFixture::route('/create'),
            'edit' => EditFixture::route('/{record}/edit'),
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
