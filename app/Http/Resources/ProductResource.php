<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'barcode' => $this->barcode,
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => $this->category,
            'low_stock' => $this->isLowStock(),
        ];
    }
}
