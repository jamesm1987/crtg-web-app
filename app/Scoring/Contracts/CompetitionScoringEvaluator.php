<?php

namespace App\Scoring\Contracts;

use App\Models\Competition;
use App\Scoring\ScoringAward;
use Illuminate\Support\Collection;

interface CompetitionScoringEvaluator extends ScoringEvaluator
{
    /** @return Collection<int, ScoringAward> */
    public function evaluate(Competition $competition, Collection $rules): Collection;
}