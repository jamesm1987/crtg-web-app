<?php

namespace App\Jobs;

use App\Models\Competition;
use App\Models\ScoringRule;
use App\Models\TeamPointsLedger;
use App\Scoring\Contracts\CompetitionScoringEvaluator;
use App\Scoring\ScoringAward;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateCompetitionConclusionPoints implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Competition $competition)
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


        $awards = collect(config('scoring.competition_evaluators'))
            ->map(fn (string $class) => app($class))
            ->flatMap(fn (CompetitionScoringEvaluator $evaluator) => $evaluator->evaluate(
                $this->competition,
                $rulesByCategory->get($evaluator->category(), collect())
            ));

        $awards->each(fn (ScoringAward $award) => TeamPointsLedger::updateOrCreate(
            [
                'team_id' => $award->teamId,
                'fixture_id' => null,
                'scoring_rule_id' => $award->rule->id,
            ],
            [
                'competition_id' => $this->competition->id,
                'points' => $award->rule->points,
                'earned_at' => now(),
                'source' => 'api',
            ]
        ));
    }
}
