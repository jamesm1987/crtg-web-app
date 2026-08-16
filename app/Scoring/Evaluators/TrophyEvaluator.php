<?php

namespace App\Scoring\Evaluators;

use App\Models\Competition;
use App\Scoring\Contracts\CompetitionScoringEvaluator;
use App\Scoring\ScoringAward;
use Illuminate\Support\Collection;

final class TrophyEvaluator implements CompetitionScoringEvaluator
{
    public function category(): string 
    { 
        return 'trophy'; 
    }
    
    public function categoryLabel(): string 
    { 
        return 'Trophy Bonus Points'; 
    }

    public function codes(): array
    {
        return ['trophy' => 'Competition Winner'];
    }

    public function thresholdLabel(): ?string 
    { 
        return null; 
    }

    public function evaluate(Competition $competition, Collection $rules): Collection 
    {
        if (!$competition->trophy_scoring_rule_id || $competition->winner_team_id) {
            return collect();
        }

        $rule = $rules->firstWhere('id', $competition->trophy_scoring_rule_id);

        if (!$rule || !$rule->is_active) {
            return collect();
        }

        return collect([new ScoringAward($competition->winner_team_id, $rule)]);
    }
}