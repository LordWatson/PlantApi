<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        $returnArray = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->when($request->user()->isAdmin(), RoleResource::make($this->roles())),
        ];

        return $returnArray;
    }
}
