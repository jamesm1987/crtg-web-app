<?php

namespace App\Filament\Resources\ScoringRules\Schemas;

use App\Scoring\ScoringEvaluatorRegistry;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Competition;

class ScoringRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        $registry = app(ScoringEvaluatorRegistry::class);

        return $schema
            ->components([
                Section::make('Rule Definition')
                    ->description('Configure when this rule triggers.')
                    ->schema([
                        Select::make('category')
                            ->options($registry->categoryOptions())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('code', null)),

                        Select::make('code')
                            ->label('Rule')
                            ->options(fn (Get $get): array => filled($get('category'))
                                ? $registry->codeOptions($get('category'))
                                : [])
                            ->required()
                            ->live()
                            ->disabled(fn (Get $get): bool => blank($get('category'))),

                        TextInput::make('threshold')
                            ->numeric()
                            ->label(fn (Get $get): string => filled($get('category'))
                                ? ($registry->thresholdLabel($get('category')) ?? 'Threshold')
                                : 'Threshold')
                            ->visible(fn (Get $get): bool => filled($registry->thresholdLabel($get('category') ?? '')))
                            ->required(fn (Get $get): bool => filled($registry->thresholdLabel($get('category') ?? '')))
                            ->live(onBlur: true),
                    ])
                    ->columns(2),

                Section::make('Points')
                    ->schema([
                        TextInput::make('points')
                            ->numeric()
                            ->required()
                            ->suffix('pts')
                            ->live(onBlur: true)
                            ->helperText('Use a negative value for penalty rules (e.g. heavy defeats).'),

                        TextInput::make('label')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Admin-facing description, shown in the points ledger and audit trail.'),

                        Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Inactive rules are ignored by the calculation engine but kept for history.'),
                    ])
                    ->columns(2),

                Section::make('Applies To')
                    ->description('Select every competition this trophy bonus applies to.')
                    ->schema([
                        Select::make('competition_ids')
                            ->label('Competitions')
                            ->multiple()
                            ->searchable()
                            ->options(fn () => Competition::query()->pluck('name', 'id'))
                            ->helperText('E.g. select all domestic leagues for "Domestic League Winner".'),
                    ])
                    ->visible(fn (Get $get): bool => $get('category') === 'trophy'),

                Placeholder::make('preview')
                    ->label('Rule summary')
                    ->content(function (Get $get) use ($registry): string {
                        if (blank($get('category')) || blank($get('code'))) {
                            return 'Select a category and rule to see a summary.';
                        }

                        $codeLabel = $registry->codeOptions($get('category'))[$get('code')] ?? $get('code');
                        $threshold = filled($get('threshold')) ? " ({$get('threshold')})" : '';
                        $points = $get('points') ?? '?';
                        $sign = is_numeric($points) && $points > 0 ? '+' : '';

                        return "{$codeLabel}{$threshold} → {$sign}{$points} points";
                    })
                    ->live(),
            ]);
    }
}