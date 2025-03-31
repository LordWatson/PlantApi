<?php

namespace App\Services\Api;

use App\Services\Api\Clients\PerenualApiClient;
use App\Services\Api\DTOs\PerenualSingleSpeciesData;
use App\Services\Api\DTOs\PerenualSpeciesData;
use App\Services\Api\Responses\PerenualApiResponse;

class PerenualApiService
{
    public function __construct(protected PerenualApiClient $client)
    {
        //
    }

    public function getSpecies(string $search = '', int $page = 1, int $perPage = 10): PerenualApiResponse
    {
        $response = $this->client->getSpecies([
            'q' => $search,
            'page' => $page,
            'per_page' => $perPage,
        ]);

        return new PerenualApiResponse(
            array_map(fn($item) => PerenualSpeciesData::getSpeciesFromApi($item), $response['data']),
        );
    }

    public function getSingleSpecies(int $speciesId): PerenualApiResponse
    {
        $response = $this->client->getSingleSpecies(speciesId: $speciesId);

        return new PerenualApiResponse([
            PerenualSingleSpeciesData::getSingleSpeciesFromApi($response),
        ]);
    }
}
