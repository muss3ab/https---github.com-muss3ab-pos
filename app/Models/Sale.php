<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method'
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->saleItems->sum('subtotal');
    }

    public function getChangeAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getPaymentMethodAttribute()
    {
        return $this->payment_method ?? 'Cash';
    }

    public function getInvoiceNumberAttribute()
    {
        return 'INV-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
