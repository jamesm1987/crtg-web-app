<?php

namespace App\Services\Standings;

use App\Models\Competition;
use App\Models\Fixture;
use Illuminate\Support\Collection;

class StandingsCalculator
{
    public function calculate(Competition $competition): Collection
    {
        $table = [];

        foreach ($competition->teams as $team) {
            $table[$team->id] = $this->emptyRow($team);
        }

        $fixtures = Fixture::query()
            ->where('competition_id', $competition->id)
            ->whereNotNull('home_team_score')
            ->whereNotNull('away_team_score')
            ->with(['homeTeam', 'awayTeam'])
            ->get();

        foreach ($fixtures as $fixture) {
            $homeId = $fixture->home_team_id;
            $awayId = $fixture->away_team_id;

            $table[$homeId] ??= $this->emptyRow($fixture->homeTeam);
            $table[$awayId] ??= $this->emptyRow($fixture->awayTeam);

            $table[$homeId]['played']++;
            $table[$awayId]['played']++;

            $table[$homeId]['gf'] += $fixture->home_team_score;
            $table[$homeId]['ga'] += $fixture->away_team_score;
            $table[$awayId]['gf'] += $fixture->away_team_score;
            $table[$awayId]['ga'] += $fixture->home_team_score;

            if ($fixture->home_team_score > $fixture->away_team_score) {
                $table[$homeId]['won']++;
                $table[$homeId]['points'] += 3;
                $table[$awayId]['lost']++;
            } elseif ($fixture->home_team_score < $fixture->away_team_score) {
                $table[$awayId]['won']++;
                $table[$awayId]['points'] += 3;
                $table[$homeId]['lost']++;
            } else {
                $table[$homeId]['drawn']++;
                $table[$awayId]['drawn']++;
                $table[$homeId]['points']++;
                $table[$awayId]['points']++;
            }
        }

        return collect($table)
            ->map(fn ($row) => array_merge($row, [
                'gd' => $row['gf'] - $row['ga'],
            ]))
            ->sort(function ($a, $b) {
                return [$b['points'], $b['gd'], $b['gf']] <=> [$a['points'], $a['gd'], $a['gf']]
                    ?: $a['team']->name <=> $b['team']->name;
            })
            ->values();
    }

    private function emptyRow($team): array
    {
        return [
            'team'   => $team,
            'played' => 0,
            'won'    => 0,
            'drawn'  => 0,
            'lost'   => 0,
            'gf'     => 0,
            'ga'     => 0,
            'gd'     => 0,
            'points' => 0,
        ];
    }
}