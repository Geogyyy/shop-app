<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Picture;
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name"=> $this->name,
            "category_id"=> $this->category_id,
            "description" => $this->description,
            "price"=> $this->price,
            "stock"=> $this->stock,
        ];
    }
}
