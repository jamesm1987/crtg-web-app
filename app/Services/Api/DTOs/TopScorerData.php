<?php

namespace App\Services\Api\DTOs;

use Illuminate\Support\Collection;

class TopScorerData
{
    public function __construct(
        public string $playerName,
        public int $teamApiId,
        public int $goals,
    ) {}

    public static function fromApiResponse(array $raw): self
    {
        return new self(
            playerName: $raw['player']['name'],
            teamApiId: $raw['statistics'][0]['team']['id'],
            goals: $raw['statistics'][0]['goals']['total'],
        );
    }

    /**
    * @return Collection<int, self>
    */
    public static function collectionFromResponse(array $response): Collection
    {
        return collect($response)->map(fn (array $row) => self::fromApiResponse($row));
    }
}