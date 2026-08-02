<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use App\Services\Api\DTOs\TeamData;
use App\Services\Api\DTOs\FixtureData;
use Illuminate\Support\Collection;

class FootballApiClient
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.football_api.base_url');
        $this->apiKey = config('services.football_api.key');
    }

    protected function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeaders(['x-apisports-key' => $this->apiKey])
            ->throw();
    }

    private function fetch(string $endpoint, int $leagueId, int $season): array
    {
        return $this->client()
            ->withQueryParameters([
                'league' => $leagueId,
                'season' => $season,
            ])
            ->get($endpoint)
            ->json('response', []);
    }

    public function fetchLeagueTable(int $leagueId, int $season): Collection
    {
        return $this->fetch('standings', $leagueId, $season);
    }    

    public function fetchTeams(int $leagueId, int $season): Collection
    {
        
        return TeamData::collectionFromResponse(
            $this->fetch('teams', $leagueId, $season)
        );
    }

    public function fetchFixtures(int $leagueId, int $season): Collection
    {
        return FixtureData::collectionFromResponse(
            $this->fetch('fixtures', $leagueId, $season)
        );
    }

    public function fetchTopScorers(int $leagueId, int $season): Collection
    {
        return $this->fetch('players/topscorers', $leagueId, $season);            
    }
}