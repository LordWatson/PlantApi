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
        $isAdmin = $request->user()->isAdmin();
        $isRolesPath = str_contains($request->path(), '/roles');

        return [
            'id' => $this->when($isAdmin && $isRolesPath, $this->id),
            'name' => $this->name,
            'description' => $this->when($isRolesPath, $this->description),
            'level' => $this->when($isAdmin && $isRolesPath, $this->level),
        ];
    }
}
