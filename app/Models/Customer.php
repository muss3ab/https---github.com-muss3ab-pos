<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'email',
        'phone',
        'address',
        'total_spent',
        'total_visits',
        'last_visit',
        'notes'
    ];

    protected $casts = [
        'last_visit' => 'date',
        'total_spent' => 'decimal:2'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function updateStats()
    {
        $this->update([
            'total_spent' => $this->sales->sum('total_amount'),
            'total_visits' => $this->sales->count(),
            'last_visit' => $this->sales->max('created_at')
        ]);
    }
}
