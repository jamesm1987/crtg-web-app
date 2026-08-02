<?php

namespace App\Filament\Resources\Competitions\Resources\Teams\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('display_name'),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('£'),
                TextInput::make('logo_url')
                    ->url(),
            ]);
    }
}
