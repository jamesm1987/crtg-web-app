<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use App\Models\Setting;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::first();

        $this->form->fill($settings ? $settings->toArray() : [
            'season' => '2026',
            'budget' => 125,
            'entry_fee' => 21,
            'teams_per_league' => 2,
        ]);
    }

    public function form(Schema $schema): Schema
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
                                    ->numeric(), // Fixed: ->integer() is not a native method

                                TextInput::make('budget')
                                    ->prefix('£')
                                    ->suffix('m')
                                    ->numeric(),

                                TextInput::make('entry_fee')
                                    ->prefix('£')
                                    ->numeric(),

                                TextInput::make('teams_per_league')
                                    ->numeric(),  
                            ]),

                        Fieldset::make('Transfer Window')
                            ->schema([
                                DatePicker::make('transfer_window_open_at')
                                    ->label('Opens'),

                                DatePicker::make('transfer_window_close_at')
                                    ->label('Closes'),
                            ])
                            ->columns(2),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $state = $this->form->getState();

            Setting::updateOrCreate(['id' => 1], $state);

            Notification::make()
                ->success()
                ->title('Settings saved successfully')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error saving settings')
                ->body($e->getMessage())
                ->send();
        }
    }
}