<?php

namespace App\Services\Api\Clients;

use Illuminate\Support\Facades\Http;
use App\Services\Api\Exceptions\ApiException;

class PerenualApiClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.perenual.url');
        $this->apiKey = config('services.perenual.key');
        $this->timeout = 30;
    }

    public function getSpecies(array $params = []): array
    {
        $params['key'] = $this->apiKey;

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])
                ->timeout($this->timeout)
                ->get("{$this->baseUrl}/species-list", $params);

            if ($response->failed()) {
                throw new ApiException(
                    "API request failed: " . $response->body(),
                    $response->status()
                );
            }

            return $response->json();
        } catch (\Exception $e) {
            throw new ApiException("API connection failed: " . $e->getMessage());
        }
    }
}
