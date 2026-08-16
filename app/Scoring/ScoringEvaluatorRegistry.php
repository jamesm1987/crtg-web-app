<?php

namespace App\Scoring;

use App\Scoring\Contracts\ScoringEvaluator;
use Illuminate\Support\Collection;

final class ScoringEvaluatorRegistry
{
    /** @var Collection<ScoringEvaluator> */
    private Collection $evaluators;

    public function __construct()
    {
        $this->evaluators = collect([
            ...config('scoring.fixture_evaluators'),
            ...config('scoring.competition_evaluators'),
        ])->map(fn (string $class) => app($class));
    }

    public function categoryOptions(): array
    {
        return $this->evaluators
            ->mapWithKeys(fn (ScoringEvaluator $e) => [$e->category() => $e->categoryLabel()])
            ->all();
    }

    public function codeOptions(string $category): array
    {
        return $this->forCategory($category)?->codes() ?? [];
    }

    public function thresholdLabel(string $category): ?string
    {
        return $this->forCategory($category)?->thresholdLabel();
    }

    public function usesCompetitionType(string $category): bool
    {
        return $this->forCategory($category)?->usesCompetitionType() ?? false;
    }

    private function forCategory(string $category): ?ScoringEvaluator
    {
        return $this->evaluators->first(fn (ScoringEvaluator $e) => $e->category() === $category);
    }
}