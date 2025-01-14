<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city' => $this->city,
            'is_favorite' => $this->is_favorite,
            'send_forecast' => $this->send_forecast,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
