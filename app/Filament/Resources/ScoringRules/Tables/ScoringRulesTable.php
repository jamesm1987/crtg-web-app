<?php

namespace App\Filament\Resources\ScoringRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;

use App\Scoring\ScoringEvaluatorRegistry;

class ScoringRulesTable
{
    public static function configure(Table $table): Table
    {
        $registry = app(ScoringEvaluatorRegistry::class);

        return $table
            ->columns([
                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'result' => 'gray',
                        'score_margin' => 'info',
                        'goalscorer' => 'warning',
                        'trophy' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => $registry->categoryOptions()[$state] ?? $state),

                TextColumn::make('label')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('competitionType.name')
                    ->label('Competition Type')
                    ->placeholder('All competitions')
                    ->toggleable(),

                TextColumn::make('threshold')
                    ->placeholder('—')
                    ->alignCenter(),

                TextColumn::make('points')
                    ->badge()
                    ->color(fn (int $state): string => $state < 0 ? 'danger' : 'success')
                    ->formatStateUsing(fn (int $state): string => $state > 0 ? "+{$state}" : (string) $state)
                    ->alignCenter(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
