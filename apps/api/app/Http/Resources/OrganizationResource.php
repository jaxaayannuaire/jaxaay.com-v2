<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray($request): array
    {
        return ['public_id' => $this->public_id, 'name' => $this->name, 'slug' => $this->slug, 'status' => $this->status, 'role' => $this->when($this->pivot, fn () => $this->pivot->role)];
    }
}
