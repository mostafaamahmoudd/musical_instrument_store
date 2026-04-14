<?php

namespace App\Http\Resources\Api;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstrumentResource extends JsonResource
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
            'serial_number' => $this->serial_number,
            'sku' => $this->sku,
            'price' => $this->price,
            'condition' => $this->condition,
            'stock_status' => $this->stock_status,
            'year_made' => $this->year_made?->format('Y-m-d'),
            'quantity' => $this->quantity,
            'featured' => (bool) $this->featured,
            'published_at' => $this->published_at?->toISOString(),
            'spec' => $this->whenLoaded('spec', fn() => new InstrumentSpecResource($this->spec)),
            'images' => $this->whenLoaded('media', fn() => $this->media
                ->where('collection_name', 'gallery')
                ->map(fn (Media $media) => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'original_url' => $media->getUrl(),
                    'preview_url' => $media->hasGeneratedConversion('preview')
                        ? $media->getUrl('preview')
                        : $media->getUrl(),
                    'thumb_url' => $media->hasGeneratedConversion('thumb')
                        ? $media->getUrl('thumb')
                        : $media->getUrl(),
                ])
                ->values()),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
