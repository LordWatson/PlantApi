<?php

namespace App\Services\Api;

use App\Services\Api\Clients\PerenualApiClient;
use App\Services\Api\DTOs\PerenualData;
use App\Services\Api\Responses\PerenualApiResponse;

class PerenualApiService
{
    public function __construct(protected PerenualApiClient $client)
    {
    }

    public function getSpecies(): PerenualApiResponse
    {
        $response = $this->client->getSpecies();

        return new PerenualApiResponse(
            data: array_map(fn($item) => PerenualData::getSpeciesfromApi($item), $response['data']),
            meta: $response['meta'] ?? []
        );
    }
}
