<?php

namespace App\Services\Api\Clients;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\Api\Exceptions\ApiException;

class PerenualApiClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;
    protected int $cacheDuration;

    public function __construct()
    {
        $this->baseUrl = config('services.perenual.url');
        $this->apiKey = config('services.perenual.key');
        $this->timeout = config('services.perenual.timeout', 30);
        $this->cacheDuration = config('services.perenual.cache_duration', 300);
    }

    public function getSpecies(array $params = []): array
    {
        if(isset($params['q']) && !empty($params['q'])){
            $params['q'] = urlencode($params['q']);
        }

        return $this->handleApiRequest(
            endpoint: '/species-list',
            cachePrefix: 'perenual:species-list',
            params: $params
        );
    }

    public function getSingleSpecies(int $speciesId, array $params = []): array
    {
        return $this->handleApiRequest(
            endpoint: "/species/details/{$speciesId}",
            cachePrefix: "perenual:single-species-{$speciesId}",
            params: $params
        );
    }

    protected function handleApiRequest(string $endpoint, string $cachePrefix, array $params = []): array
    {
        $params['key'] = $this->apiKey;

        // create a unique cache key
        $cacheKey = $this->buildCacheKey($cachePrefix, $params);

        // check for existing cached response
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($endpoint, $params) {
            return $this->makeHttpRequest($endpoint, $params);
        });
    }

    protected function makeHttpRequest(string $endpoint, array $params): array
    {
        try {
            $response = Http::withHeaders(['Accept' => 'application/json'])
                ->timeout($this->timeout)
                ->get("{$this->baseUrl}{$endpoint}", $params);

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

    protected function buildCacheKey(string $prefix, array $params): string
    {
        return "{$prefix}_" . md5(json_encode($params));
    }
}
