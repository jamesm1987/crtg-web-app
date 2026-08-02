<?php

namespace App\Services\Api\DTOs\Contracts;
use Illuminate\Support\Collection;

interface ApiDataTransferObject
{
    public static function fromApiResponse(array $data): static;
    
    /**
     * @return Collection<int, static>
     */
    public static function collectionFromResponse(array $response): Collection;
}

