<?php

namespace App\Scoring\Contracts;

use App\Models\Fixture;
use App\Scoring\ScoringAward;
use Illuminate\Support\Collection;

interface FixtureScoringEvaluator extends ScoringEvaluator
{
    /**
     * Evaluate a completed fixture against the given active rules for this
     * evaluator's category, returning any points to be awarded.
     *
     * @param  Collection<int, \App\Models\ScoringRule>  $rules  Active rules scoped to this evaluator's category()
     * @return Collection<int, ScoringAward>
     */
    public function evaluate(Fixture $fixture, Collection $rules): Collection;
}