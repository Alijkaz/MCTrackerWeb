<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $record = $this->resource->currentRecord();

        return [
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'address' => $this->resource->address,
            'ip' => $this->resource->ip,
            'country_code' => $this->resource->country_code,
            'region' => $this->resource->region,
            'up_from' => $this->resource->up_from,
            'latency' => $record?->latency ?: 0,
            'players' => [
                'online' => $record?->players ?: 0,
                'max' => $record?->max_players ?: 0
            ],
            'gamemodes' => $this->resource->gamemodes ? json_decode($this->resource->gamemodes) : []
        ];
    }
}
