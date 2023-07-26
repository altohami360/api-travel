<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'travel' => $this->travel->name,
            'starting_at' => $this->starting_at,
            'ending_at' => $this->ending_at,
            'price' => number_format($this->price, 2)
        ];
    }
}
