<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class DataSync extends Page
{
    protected string $view = 'filament.pages.data-sync';

    public function content(Schema $schema): Schema
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
                        ->onColor('success')
                ]);
    }
}
