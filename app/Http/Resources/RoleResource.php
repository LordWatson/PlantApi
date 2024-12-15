<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $returnArray = [
            'id' => $this->when($request->user()->isAdmin(), $this->id),
            'name' => $this->name,
            'description' => $this->description,
            'level' => $this->when($request->user()->isAdmin(), $this->level),
        ];

        return $returnArray;
    }
}
