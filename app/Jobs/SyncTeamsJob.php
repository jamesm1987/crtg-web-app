<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Competition;
use App\Services\Api\FootballApiClient;

use App\Models\Team;

class SyncTeamsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Competition $competition)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(FootballApiClient $client): void
    {
        $teams = $client->fetchTeams($this->competition->api_id, now()->year);
        
        foreach ($teams as $teamData) {
            Team::updateOrCreate(
                ['api_id' => $teamData->apiId],
                [
                    'competition_id' => $this->competition->id,
                    'name'           => $teamData->name,
                    'logo_url'       => $teamData->logoUrl,
                ]
            );
        }
    }
}
