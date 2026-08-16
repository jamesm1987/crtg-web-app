<?php

namespace App\Scoring\Evaluators;

use App\Models\Fixture;
use App\Scoring\Contracts\FixtureScoringEvaluator;
use App\Scoring\ScoringAward;
use Illuminate\Support\Collection;

final class ResultEvaluator implements FixtureScoringEvaluator
{
    public function category(): string { return 'result'; }
    public function categoryLabel(): string { return 'Result Points'; }
    public function codes(): array { return ['win' => 'Win', 'draw' => 'Draw']; }
    public function thresholdLabel(): ?string { return null; }

    public function evaluate(Fixture $fixture, Collection $rules): Collection
    {
        $isDraw = $fixture->home_team_score === $fixture->away_team_score;

        if ($isDraw) {
            $rule = $rules->firstWhere('code', 'draw');
            return $rule
                ? collect([
                    new ScoringAward($fixture->home_team_id, $rule),
                    new ScoringAward($fixture->away_team_id, $rule),
                ])
                : collect();
        }

        $rule = $rules->firstWhere('code', 'win');
        if (! $rule) {
            return collect();
        }

        $winnerId = $fixture->home_team_score > $fixture->away_team_score
            ? $fixture->home_team_id
            : $fixture->away_team_id;

        return collect([new ScoringAward($winnerId, $rule)]);
    }
}