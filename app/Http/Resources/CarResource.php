<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'user_id' => $this->user_id,
            'governorate' => $this->governorate?->name,
            'city' => $this->city?->name,
            'make' => $this->make?->name,
            'model' => $this->model?->name,
            'year' => $this->year,
            'kilometers' => $this->kilometers,
            'type' => $this->type,
            'color' => $this->color,
            'fuel_type' => $this->fuel_type,
            'transmission' => $this->transmission,
            'price' => $this->price,
            'contact_phone' => $this->contact_phone,
            'whatsapp_phone' => $this->whatsapp_phone,
            'description' => $this->description,
            'main_image' => $this->main_image,
            'images' => $this->images ? json_decode($this->images) : [],
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];

    }
}
