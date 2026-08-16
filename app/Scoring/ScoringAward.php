<?php

namespace App\Scoring;

final class ScoringAward
{
    public function __construct(
        public readonly int $teamId,
        public readonly ScoringRule $rule,
    ) {}
}