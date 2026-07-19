<?php

namespace App\Services\Api\DTOs;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class FixtureData
{
    public function __construct(
        public int $apiId,
        public int $homeTeamApiId,
        public int $awayTeamApiId,
        public CarbonImmutable $kickoffAt,
        public string $status,
        public ?int $homeGoals,
        public ?int $awayGoals,
    ) {}

    public static function fromApiResponse(array $raw): self
    {
        return new self(
            apiId: $raw['fixture']['id'],
            homeTeamApiId: $raw['teams']['home']['id'],
            awayTeamApiId: $raw['teams']['away']['id'],
            kickoffAt: CarbonImmutable::parse($raw['fixture']['date']),
            status: $raw['fixture']['status']['short'],
            homeGoals: $raw['goals']['home'],
            awayGoals: $raw['goals']['away'],
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