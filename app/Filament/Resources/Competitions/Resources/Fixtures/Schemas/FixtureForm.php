<?php

namespace App\Filament\Resources\Competitions\Resources\Fixtures\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FixtureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('api_id')
                    ->required()
                    ->numeric(),
                TextInput::make('home_team_id')
                    ->required()
                    ->numeric(),
                TextInput::make('away_team_id')
                    ->required()
                    ->numeric(),
                TextInput::make('home_team_score')
                    ->numeric(),
                TextInput::make('away_team_score')
                    ->numeric(),
                DateTimePicker::make('kick_off_at')
                    ->required(),
            ]);
    }
}
