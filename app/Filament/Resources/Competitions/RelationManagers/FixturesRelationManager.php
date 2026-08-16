<?php

namespace App\Filament\Resources\Competitions\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Jobs\SyncFixturesJob;
use Filament\Notifications\Notification;

class FixturesRelationManager extends RelationManager
{
    protected static string $relationship = 'fixtures';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('home_team')
                    ->label('Home Team')
                    ->content(fn ($record) => $record?->homeTeam?->name),
                Placeholder::make('away_team')
                    ->label('Away Team')
                    ->content(fn ($record) => $record?->awayTeam?->name),
                TextInput::make('home_team_score')
                    ->numeric()
                    ->nullable(),
                TextInput::make('away_team_score')
                    ->numeric()
                    ->nullable(),
                DateTimePicker::make('kick_off_at')
                    ->seconds(false)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('homeTeam.name')
                    ->label('Home Team')
                    ->sortable(),
                TextColumn::make('awayTeam.name')
                    ->label('Away Team')
                    ->sortable(),
                TextColumn::make('home_team_score')
                    ->numeric(),
                TextColumn::make('away_team_score')
                    ->numeric(),
                TextColumn::make('kick_off_at')
                    ->timezone('Europe/London')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
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
            ->headerActions([
                Action::make('syncFixtures')
                ->label('Sync fixtures')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading('Sync fixtures from API')
                ->modalDescription(fn () => "This will pull all fixtures for {$this->getOwnerRecord()->name} from API-Football.")
                ->action(function () {
                    SyncFixturesJob::dispatch($this->getOwnerRecord());

                    Notification::make()
                        ->title('Sync queued')
                        ->body('Fixtures are being pulled from API-Football.')
                        ->success()
                        ->send();
                }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
