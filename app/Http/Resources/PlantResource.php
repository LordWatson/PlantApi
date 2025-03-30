<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $returnArray = [
            'id' => $this->id,
            'perenual_id' => $this->perenual_id,
            'name' => $this->name,
            'scientific_name' => $this->latin_name,
            'watering' => $this->watering,
            'watering_period' => $this->watering_period,
            'sunlight' => $this->sunlight,
            'humidity' => $this->humidity,
            'care_level' => $this->care_level,
        ];

        return $returnArray;
    }
}
