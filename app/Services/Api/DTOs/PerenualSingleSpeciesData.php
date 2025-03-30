<?php

namespace App\Services\Api\DTOs;

use Spatie\LaravelData\Data;

class PerenualSingleSpeciesData extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $common_name,
        public ?string $scientific_name,
        public ?string $watering,
        public ?string $watering_unit,
        public ?array $sunlight,
        public ?array $origin,
        public ?string $type,
        public ?string $maintenance,
        public ?string $guide,
        public ?bool $indoor,
        public ?string $care_level,
        public ?bool $flowers,
        public ?string $flower_color,
        public ?bool $leaf,
        public ?array $leaf_color,
        public ?bool $medicinal,
        public ?bool $poisonous_to_humans,
        public ?bool $poisonous_to_pets,
        public ?string $description,
    ) {}

    public static function getSingleSpeciesFromApi(array $data): self
    {
        return new self(
            id: self::getValue($data, 'id'),
            common_name: ucfirst(self::getValue($data, 'common_name')),
            scientific_name: $data['scientific_name'][0] ?? null,
            watering: self::getValue($data, 'watering'),
            watering_unit: ucfirst(self::getNestedValue($data, ['watering_general_benchmark', 'unit'])),
            sunlight: self::getValue($data, 'sunlight', []),
            origin: self::getValue($data, 'origin', []),
            type: self::getValue($data, 'type'),
            maintenance: self::getValue($data, 'maintenance'),
            guide: self::getValue($data, 'care-guides'),
            indoor: self::getValue($data, 'indoor', false),
            care_level: self::getValue($data, 'care_level'),
            flowers: self::getValue($data, 'flowers', false),
            flower_color: self::getValue($data, 'flower_color'),
            leaf: self::getValue($data, 'leaf', false),
            leaf_color: self::getValue($data, 'leaf_color', []),
            medicinal: self::getValue($data, 'medicinal', false),
            poisonous_to_humans: self::getValue($data, 'poisonous_to_humans', false),
            poisonous_to_pets: self::getValue($data, 'poisonous_to_pets', false),
            description: self::getValue($data, 'description'),
        );
    }

    private static function getValue(array $data, string $key, mixed $default = null): mixed
    {
        return $data[$key] ?? $default;
    }

    private static function getNestedValue(array $data, array $keys, mixed $default = null): mixed
    {
        $value = $data;
        foreach ($keys as $key) {
            if (!is_array($value) || !array_key_exists($key, $value)) {
                return $default;
            }
            $value = $value[$key];
        }
        return $value;
    }
}
