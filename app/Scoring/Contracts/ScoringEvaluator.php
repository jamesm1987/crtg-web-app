<?php

namespace App\Scoring\Contracts;

interface ScoringEvaluator
{
    public function category(): string;
    public function categoryLabel(): string;
    public function codes(): array;
    public function thresholdLabel(): ?string;
}
