<?php

namespace App\Http\Resources\auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'    => $this->name,
            'email'   => $this->email,
            'id'      => $this->id,
            'role'    => $this->role
        ];
    }
}
