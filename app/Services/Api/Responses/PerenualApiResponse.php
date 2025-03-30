<?php

namespace App\Services\Api\Responses;

class PerenualApiResponse
{
    public function __construct(
        public array $data,
        public array $meta = []
    ) {}

    public function toArray(): array
    {
        return $this->data;
    }
}
