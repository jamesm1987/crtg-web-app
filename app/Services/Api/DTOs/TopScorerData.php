<?php

namespace App\Services\Api\DTOs;

use Illuminate\Support\Collection;
use App\Services\Api\DTOs\Contracts\ApiDataTransferObject;

class TopScorerData implements ApiDataTransferObject
{
    public function __construct(
        public string $playerName,
        public int $teamApiId,
        public int $goals,
    ) {}

    public static function fromApiResponse(array $raw): static
    {
        return new static(
            playerName: $raw['player']['name'],
            teamApiId: $raw['statistics'][0]['team']['id'],
            goals: $raw['statistics'][0]['goals']['total'],
        );
    }

    /**
    * @return Collection<int, static>
    */
    public static function collectionFromResponse(array $response): Collection
    {
        return collect($response)->map(fn (array $row) => static::fromApiResponse($row));
    }
}