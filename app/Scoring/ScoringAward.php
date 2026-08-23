<?php

namespace App\Scoring;

use App\Models\ScoringRule;

final class ScoringAward
{
    public function __construct(
        public readonly int $teamId,
        public readonly ScoringRule $rule,
    ) {}
}