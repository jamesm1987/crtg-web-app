<?php

namespace App\Observers;

use App\Models\Fixture;
use App\Jobs\CalculateFixtureScoringPoints;

class FixtureObserver
{
    public function updated(Fixture $fixture): void
    {
        if (
            $fixture->wasChanged(['home_team_score', 'away_team_score']) &&
            $fixture->home_team_score !== null &&
            $fixture->away_team_score !== null
        ) {
            CalculateFixtureScoringPoints::dispatch($fixture);
        }
    }
}