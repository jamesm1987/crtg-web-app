<?php

namespace App\Services\Api\DTOs;

use Illuminate\Support\Collection;
use App\Services\Api\DTOs\Contracts\ApiDataTransferObject;

class StandingData implements ApiDataTransferObject
{
    public function __construct(
        public int $rank,
        public int $teamApiId,
        public string $teamName,
        public int $points,
        public int $goalsDiff,
        public int $played,
        public int $won,
        public int $drawn,
        public int $lost,
    ) {}

    public static function fromApiResponse(array $raw): static
    {
        return new static(
            rank: $raw['rank'],
            teamApiId: $raw['team']['id'],
            teamName: $raw['team']['name'],
            points: $raw['points'],
            goalsDiff: $raw['goalsDiff'],
            played: $raw['all']['played'],
            won: $raw['all']['win'],
            drawn: $raw['all']['draw'],
            lost: $raw['all']['lose'],
        );
    }

    /**
    * @return Collection<int, static>
    */
    public static function collectionFromResponse(array $response): Collection
    {
        $rows = $response[0]['league']['standings'][0] ?? [];

        return collect($rows)->map(fn (array $row) => static::fromApiResponse($row));
        
    }
}