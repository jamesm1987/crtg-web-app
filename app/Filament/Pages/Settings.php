<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class Settings extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected string $view = 'filament.pages.settings';

    public function save()
    {
        
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Game Settings')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('season')
                                    ->minLength(4)
                                    ->maxLength(4)
                                    ->integer(),

                                TextInput::make('budget')
                                    ->prefix('£')
                                    ->suffix('m'),
                            ]),

                        Fieldset::make('Transfer Window')
                            ->schema([
                                DatePicker::make('transfer_window_open')
                                    ->label('Opens'),

                                DatePicker::make('transfer_window_close')
                                    ->label('Closes'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
