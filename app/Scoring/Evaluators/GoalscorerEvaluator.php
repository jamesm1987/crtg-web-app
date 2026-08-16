<?php

namespace App\Scoring\Evaluators;

use App\Models\Team;
use App\Models\Competition;
use App\Scoring\Contracts\CompetitionScoringEvaluator;
use App\Scoring\ScoringAward;
use App\Services\Api\DTOs\TopScorerData;
use App\Services\Api\FootballApiClient;
use App\Services\Standings\StandingsCalculator;
use Illuminate\Support\Collection;

final class GoalscorerEvaluator implements CompetitionScoringEvaluator
{
    public function __construct(
        private readonly FootballApiClient $apiClient,
        private readonly StandingsCalculator $standingsCalculator,
    ) {}

    public function category(): string
    {
        return 'goalscorer';
    }

    public function categoryLabel(): string
    {
        return 'Goalscorer Points';
    }

    public function codes(): array
    {
        return ['top_scorer' => 'Top Scorer Rank'];
    }

    public function thresholdLabel(): ?string
    {
        return 'Rank (1st, 2nd, 3rd...)';
    }

    public function evaluate(Competition $competition, Collection $rules): Collection
    {
        if (! $competition->api_id ) {
            return collect();
        }

        $scorers = $this->apiClient->fetchTopScorers(
            $competition->api_id,
            Setting::get('season'),
        );

        if ($scorers->isEmpty()) {
            return collect();
        }

        $standings = $this->standingsCalculator->calculate($competition);

        $positionByApiTeamId = $standings
            ->values()
            ->mapWithKeys(fn ($row, $index) => [$row['team']->api_id => $index]);

        $rankedScorers = $scorers
            ->sortBy([
                fn (TopScorerData $a, TopScorerData $b) => $b->goals <=> $a->goals,

                fn (TopScorerData $a, TopScorerData $b) =>
                    ($positionByApiTeamId[$b->teamApiId] ?? -1)
                        <=> ($positionByApiTeamId[$a->teamApiId] ?? -1),
            ])
            ->values();

        return $rankedScorers
            ->take(3)
            ->map(function (TopScorerData $scorer, int $index) use ($rules) {
                $rank = $index + 1;
                $rule = $rules->firstWhere('threshold', $rank);

                if (! $rule) {
                    return null;
                }

                $team = Team::query()->where('api_id', $scorer->teamApiId)->first();

                return $team ? new ScoringAward($team->id, $rule) : null;
            })
            ->filter()
            ->values();
    }
}