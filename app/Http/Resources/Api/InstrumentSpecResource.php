<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstrumentSpecResource extends JsonResource
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
            'model' => $this->model,
            'num_strings' => $this->num_strings,
            'style' => $this->style,
            'finish' => $this->finish,
            'description' => $this->description,
            'family' => $this->whenLoaded('instrumentFamily', fn() => [
                'id' => $this->instrumentFamily?->id,
                'name' => $this->instrumentFamily?->name,
                'slug' => $this->instrumentFamily?->slug,
            ]),
            'builder' => $this->whenLoaded('builder', fn() => [
                'id' => $this->builder?->id,
                'name' => $this->builder?->name,
                'slug' => $this->builder?->slug,
                'country' => $this->builder?->country,
            ]),
            'type' => $this->whenLoaded('instrumentType', fn() => [
                'id' => $this->instrumentType?->id,
                'name' => $this->instrumentType?->name,
                'slug' => $this->instrumentType?->slug,
            ]),
            'top_wood' => $this->whenLoaded('topWood', fn() => [
                'id' => $this->topWood?->id,
                'name' => $this->topWood?->name,
                'slug' => $this->topWood?->slug,
            ]),
            'back_wood' => $this->whenLoaded('backWood', fn() => [
                'id' => $this->backWood?->id,
                'name' => $this->backWood?->name,
                'slug' => $this->backWood?->slug,
            ]),
        ];
    }
}
