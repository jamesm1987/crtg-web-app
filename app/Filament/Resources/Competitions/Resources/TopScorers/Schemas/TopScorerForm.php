<?php

namespace App\Filament\Resources\Competitions\Resources\TopScorers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TopScorerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('player_name')
                    ->label('name')
                    ->required(),
                TextInput::make('goals')
                    ->required()
            ]);
    }
}
