<?php

namespace App\Jobs;

use App\Models\Fixture;
use App\Models\ScoringRule;
use App\Models\TeamPointsLedger;
use App\Scoring\Contracts\FixtureScoringEvaluator;
use App\Scoring\ScoringAward;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateFixtureScoringPoints implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Fixture $fixture)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rulesByCategory = ScoringRule::query()
            ->where('is_active', true)
            ->get()
            ->groupBy('category');

        $awards = collect(config('scoring.fixture_evaluators'))
            ->map(fn (string $class) => app($class))
            ->flatMap(fn (FixtureScoringEvaluator $evaluator) => $evaluator->evaluate(
                $this->fixture,
                $rulesByCategory->get($evaluator->category(), collect())
            ));

        $awards->each(fn (ScoringAward $award) => TeamPointsLedger::updateOrCreate(
            [
                'team_id' => $award->teamId,
                'fixture_id' => $this->fixture->id,
                'scoring_rule_id' => $award->rule->id,
            ],
            [
                'competition_id' => $this->fixture->competition_id,
                'points' => $award->rule->points,
                'earned_at' => $this->fixture->kick_off_at,
                'source' => 'api',
            ]
        ));
    }
}
