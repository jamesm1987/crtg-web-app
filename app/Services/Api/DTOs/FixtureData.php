<?php

namespace App\Services\Api\DTOs;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use App\Services\Api\DTOs\Contracts\ApiDataTransferObject;

class FixtureData implements ApiDataTransferObject
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

    public static function fromApiResponse(array $raw): static
    {
        return new static(
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
    * @return Collection<int, static>
    */
    public static function collectionFromResponse(array $response): Collection
    {
        return collect($response)->map(fn (array $row) => static::fromApiResponse($row));
    }
}