<?php

namespace App\Scoring\Evaluators;

use App\Models\Fixture;
use App\Scoring\Contracts\FixtureScoringEvaluator;
use App\Scoring\ScoringAward;
use Illuminate\Support\Collection;

final class ScoreMarginEvaluator implements FixtureScoringEvaluator
{

    public function category(): string { return 'score_margin'; }
    public function categoryLabel(): string { return 'Score Points'; }
    public function codes(): array
    {
        return [
            'home_win_margin' => 'Home win by X+ goals',
            'away_win_margin' => 'Away win by X+ goals',
            'home_defeat_margin' => 'Home defeat by X+ goals',
            'away_defeat_margin' => 'Away defeat by X+ goals',
        ];
    }
    public function thresholdLabel(): ?string { return 'Goal margin'; }


    public function evaluate(Fixture $fixture, Collection $rules): Collection
    {
        if ($fixture->home_team_score === $fixture->away_team_score) {
            return collect();
        }

        $margin = abs($fixture->home_team_score - $fixture->away_team_score);
        $isHomeWin = $fixture->home_team_score > $fixture->away_team_score;

        $awards = collect();

        $winnerRule = $this->highestMatchingRule($rules, $isHomeWin ? 'home_win_margin' : 'away_win_margin', $margin);
        if ($winnerRule) {
            $winnerId = $isHomeWin ? $fixture->home_team_id : $fixture->away_team_id;
            $awards->push(new ScoringAward($winnerId, $winnerRule));
        }

        $loserRule = $this->highestMatchingRule($rules, $isHomeWin ? 'away_defeat_margin' : 'home_defeat_margin', $margin);
        if ($loserRule) {
            $loserId = $isHomeWin ? $fixture->away_team_id : $fixture->home_team_id;
            $awards->push(new ScoringAward($loserId, $loserRule));
        }

        return $awards;
    }

    private function highestMatchingRule(Collection $rules, string $code, int $margin): ?ScoringRule
    {
        return $rules
            ->where('code', $code)
            ->where('threshold', '<=', $margin)
            ->sortByDesc('threshold')
            ->first();
    }
}