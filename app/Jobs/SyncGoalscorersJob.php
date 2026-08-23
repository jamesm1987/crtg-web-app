<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Competition;
use App\Models\Setting;
use App\Models\Team;
use App\Models\TopScorer;
use App\Services\Api\FootballApiClient;

class SyncGoalscorersJob implements ShouldQueue
{
    use Queueable;

    private array $teamsMap = [];

    /**
     * Create a new job instance.
     */
    public function __construct(public Competition $competition)
    {
        //
    }

    private function teamsMap(): array
    {
        if (empty($this->teamsMap)) {
            $this->teamsMap = Team::where('competition_id', $this->competition->id)
                ->pluck('id', 'api_id')
                ->all();
        }

        return $this->teamsMap;
    }

    /**
     * Execute the job.
     */
    public function handle(FootballApiClient $client): void
    {
        $topScorers = $client->fetchTopScorers($this->competition->api_id, Setting::get('season'));
        $teams = $this->teamsMap();
        
        foreach ($topScorers as $topScorerData) {
            $teamId = $teams[$topScorerData->teamApiId] ?? null;

            if (! $teamId || $topScorerData->goals <= 0) {
                continue;
            }

            TopScorer::updateOrCreate(
                [
                    'competition_id' => $this->competition->id,
                    'api_id'         => $topScorerData->playerApiId,
                ],
                [
                    'team_id'     => $teamId,
                    'player_name' => $topScorerData->playerName,
                    'goals'       => $topScorerData->goals,
                ]
            );
        }
    }
}