<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'code',
        'barcode',
        'description',
        'price',
        'stock',
        'category',
        'alert_stock'
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function isLowStock()
    {
        return $this->stock <= $this->alert_stock;
    }
}
