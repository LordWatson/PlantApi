<?php

namespace App\Services\Api\DTOs;

use Spatie\LaravelData\Data;

class PerenualData extends Data
{
    public function __construct(
        public int $id,
        public string $common_name,
        public string $scientific_name,
        public string $watering,
        public array $sunlight,
    ) {}

    public static function getSpeciesfromApi(array $data): self
    {
        return new self(
            id: $data['id'],
            common_name: $data['common_name'],
            scientific_name: $data['scientific_name'][0],
            watering: $data['watering'],
            sunlight: $data['sunlight'],
        );
    }
}
