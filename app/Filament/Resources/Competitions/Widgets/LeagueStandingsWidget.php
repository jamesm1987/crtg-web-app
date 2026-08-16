<?php

namespace App\Filament\Resources\Competitions\Widgets;

use App\Models\Competition;
use App\Models\Fixture;
use App\Services\Standings\StandingsCalculator;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class LeagueStandingsWidget extends Widget
{
    protected string $view = 'filament.resources.competitions.widgets.league-standings-widget';

    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    public Collection $standings;

    public function getStandings()
    {
        return app(StandingsCalculator::class)->calculate($this->record);
    }
}