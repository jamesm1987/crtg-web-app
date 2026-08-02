<?php

namespace App\Filament\Resources\Competitions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;


use App\Filament\Exports\CompetitionExporter;
use App\Filament\Imports\CompetitionImporter;

use Illuminate\Validation\Rules\File;


class CompetitionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('api_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                IconColumn::make('track_scorers')
                    ->boolean(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ExportAction::make()
                        ->exporter(CompetitionExporter::class),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(
                        CompetitionImporter::class
                    )
                    ->fileRules([
                        File::types(['csv','xlsx'])
                    ]),
            ]);
    }
}
