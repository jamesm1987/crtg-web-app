<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Competition;
use App\Services\Api\FootballApiClient;

use App\Models\Fixture;
use App\Models\Team;

class SyncFixturesJob implements ShouldQueue
{
    use Queueable;

    private $teamsMap = [];

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
        $fixtures = $client->fetchFixtures($this->competition->api_id, now()->year);
        $teams = $this->teamsMap();
        
        foreach ($fixtures as $fixtureData) {
            Fixture::updateOrCreate(
                ['api_id' => $fixtureData->apiId],
                [
                    'competition_id'    => $this->competition->id,
                    'home_team_id'      => $teams[$fixtureData->homeTeamApiId],
                    'away_team_id'      => $teams[$fixtureData->awayTeamApiId],
                    'kick_off_at'       => $fixtureData->kickoffAt,
                    'home_team_score'   => $fixtureData->homeGoals,
                    'away_team_score'   => $fixtureData->awayGoals,
                ]
            );
        }
    }
}
