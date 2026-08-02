<?php

namespace App\Filament\Resources\Competitions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompetitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('api_id')
                    ->required()
                    ->numeric(),
                TextInput::make('country'),
                TextInput::make('type')
                    ->required(),
                Toggle::make('track_scorers')
                    ->required(),
            ]);
    }
}
