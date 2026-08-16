<?php

namespace App\Filament\Resources\ScoringRules;

use App\Filament\Resources\ScoringRules\Pages\CreateScoringRule;
use App\Filament\Resources\ScoringRules\Pages\EditScoringRule;
use App\Filament\Resources\ScoringRules\Pages\ListScoringRules;
use App\Filament\Resources\ScoringRules\Schemas\ScoringRuleForm;
use App\Filament\Resources\ScoringRules\Tables\ScoringRulesTable;
use App\Models\ScoringRule;
use App\Scoring\ScoringEvaluatorRegistry;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScoringRuleResource extends Resource
{
    protected static ?string $model = ScoringRule::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCalculator;
    
    protected static string|UnitEnum|null $navigationGroup = 'Points';

    public static function form(Schema $schema): Schema
    {
        return ScoringRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScoringRulesTable::configure($table);
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
            'index' => ListScoringRules::route('/'),
            'create' => CreateScoringRule::route('/create'),
            'edit' => EditScoringRule::route('/{record}/edit'),
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
