<?php

namespace App\Filament\Resources\TeamPointsLedgers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use App\Models\Team;
use App\Models\Competition;
use App\Scoring\ScoringEvaluatorRegistry;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TeamPointsLedgersTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('club.name')
                ->label('Team')
                ->searchable()
                ->sortable(),

            TextColumn::make('competition.name')
                ->label('Competition')
                ->searchable()
                ->toggleable(),

            TextColumn::make('scoringRule.label')
                ->label('Rule')
                ->badge(),

            TextColumn::make('fixture.display_name')
                ->label('Fixture')
                ->placeholder('Competition-end award')
                ->url(fn (ClubPointsLedger $record): ?string => $record->fixture_id
                    ? FixtureResource::getUrl('edit', ['record' => $record->fixture_id])
                    : null)
                ->openUrlInNewTab(),

            TextColumn::make('points')
                ->badge()
                ->color(fn (int $state): string => $state < 0 ? 'danger' : 'success')
                ->formatStateUsing(fn (int $state): string => $state > 0 ? "+{$state}" : (string) $state)
                ->alignCenter()
                ->sortable(),

            TextColumn::make('source')
                ->badge()
                ->color(fn (string $state): string => $state === 'admin_override' ? 'warning' : 'gray')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'admin_override' => 'Manual Override',
                    'api' => 'Automatic',
                    default => $state,
                }),

            TextColumn::make('notes')
                ->limit(40)
                ->tooltip(fn (?string $state): ?string => $state)
                ->placeholder('—')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Awarded')
                ->dateTime()
                ->since()
                ->sortable(),
            ])
            ->filters([
                SelectFilter::make('team_id')
                ->label('Team')
                ->options(fn () => Team::query()->pluck('name', 'id'))
                ->searchable(),

            SelectFilter::make('competition_id')
                ->label('Competition')
                ->options(fn () => Competition::query()->pluck('name', 'id'))
                ->searchable(),

            SelectFilter::make('category')
                ->label('Category')
                ->relationship('scoringRule', 'category')
                ->options(fn () => app(ScoringEvaluatorRegistry::class)->categoryOptions()),

            SelectFilter::make('source')
                ->options([
                    'api' => 'Automatic',
                    'admin_override' => 'Manual Override',
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('addOverride')
                ->label('Add Manual Override')
                ->icon('heroicon-o-plus')
                ->schema([
                    Select::make('team_id')
                        ->label('Team')
                        ->options(fn () => Team::query()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Select::make('competition_id')
                        ->label('Competition')
                        ->options(fn () => Competition::query()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Select::make('scoring_rule_id')
                        ->label('Rule')
                        ->relationship('scoringRule', 'label')
                        ->searchable()
                        ->required(),

                    TextInput::make('points')
                        ->numeric()
                        ->required()
                        ->suffix('pts'),

                    Textarea::make('notes')
                        ->required()
                        ->helperText('Explain why this override was necessary — this is the only context an admin will have when auditing this row later.'),
                ])
                ->action(function (array $data): void {
                    TeamPointsLedger::create([
                        ...$data,
                        'fixture_id' => null,
                        'source' => 'admin_override',
                    ]);
                }),
            ])
            ->emptyStateHeading('No points awarded yet')
            ->emptyStateDescription('Points will appear here once fixtures are completed or competitions are concluded.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}
