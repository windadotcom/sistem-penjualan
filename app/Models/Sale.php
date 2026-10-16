<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'user_id', 'total', 'paid', 'source', 'status',
        'payment_method', 'customer_name', 'customer_phone', 'customer_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // Transaksi yang tidak dibatalkan (dipakai untuk hitung pendapatan)
    public function scopeValid($query)
    {
        return $query->where('status', '!=', 'dibatalkan');
    }

    // Pesanan online tidak punya kasir, jadi tampilkan "Online"
    public function getCashierNameAttribute(): string
    {
        return $this->user?->name ?? 'Online';
    }
}