<?php

namespace App\Services\Api\DTOs;

use Illuminate\Support\Collection;

class StandingData
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

    public static function fromApiResponse(array $raw): self
    {
        return new self(
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
    * @return Collection<int, self>
    */
    public static function collectionFromResponse(array $response): Collection
    {
        $rows = $response[0]['league']['standings'][0] ?? [];

        return collect($rows)->map(fn (array $row) => self::fromApiResponse($row));
        
    }
}