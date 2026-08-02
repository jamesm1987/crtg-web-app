<?php

namespace App\Services\Api\DTOs;

use Illuminate\Support\Collection;
use App\Services\Api\DTOs\Contracts\ApiDataTransferObject;

class TeamData implements ApiDataTransferObject
{
    public function __construct(
        public int $apiId,
        public string $name,
        public string $logoUrl,
    ) {}

    public static function fromApiResponse(array $raw): static
    {
        return new static(
            apiId: $raw['id'],
            name: $raw['name'],
            logoUrl: $raw['logo'],
        );
    }

    /**
     * @return Collection<int, static>
     */
    public static function collectionFromResponse(array $response): Collection
    {
        return collect($response)->map(fn (array $row) => static::fromApiResponse($row['team']));
    }
}