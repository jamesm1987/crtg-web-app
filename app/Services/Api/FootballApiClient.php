<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

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

    public function fetchLeagueTable(int $leagueId, int $season): array
    {
        return $this->fetch('standings', $leagueId, $season);
    }    

    public function fetchTeams(int $leagueId, int $season): array
    {
        return $this->fetch('teams', $leagueId, $season);
    }

    public function fetchFixtures(int $leagueId, int $season): array
    {
        return $this->fetch('fixtures', $leagueId, $season);
    }

    public function fetchTopScorers(int $leagueId, int $season): array
    {
        return $this->fetch('players/topscorers', $leagueId, $season);            
    }
}