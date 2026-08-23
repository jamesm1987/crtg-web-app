<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Competition;
use App\Services\Api\FootballApiClient;

use App\Models\Setting;
use App\Models\Fixture;
use App\Models\Team;

class SyncFixturesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Competition $competition)
    {
        //
    }

    private function teamsMap(): array 
    {
        return Team::where('competition_id', $this->competition->id)
            ->pluck('id', 'api_id')
            ->all();
    }

    private function getCompleted(): array
    {
        return Fixture::where('competition_id', $this->competition->id)
            ->completed()
            ->pluck('api_id')
            ->toArray();
    }

    /**
     * Execute the job.
     */
    public function handle(FootballApiClient $client): void
    {
        $fixtures  = $client->fetchFixtures($this->competition->api_id, Setting::get('season'));
        $teams     = $this->teamsMap();
        $completed = $this->getCompleted();

        foreach ($fixtures as $fixtureData) {
            if (in_array($fixtureData->apiId, $completed)) {
                continue;
            }

            Fixture::updateOrCreate(
                ['api_id' => $fixtureData->apiId],
                [
                    'competition_id' => $this->competition->id,
                    'home_team_id'   => $teams[$fixtureData->homeTeamApiId],
                    'away_team_id'   => $teams[$fixtureData->awayTeamApiId],
                    'kick_off_at'    => $fixtureData->kickoffAt,
                ]
            );
        }
    }
}
