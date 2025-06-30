<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'external_id',
        'amount',
        'status',
        'payment_data',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_data' => 'array',
        'paid_at' => 'datetime'
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Accessors
    public function getIsSuccessAttribute(): bool
    {
        return $this->status === 'success';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsFailedAttribute(): bool
    {
        return in_array($this->status, ['failed', 'cancelled']);
    }

    public function getPaymentMethodNameAttribute(): string
    {
        $methods = [
            'credit_card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'e_wallet' => 'E-Wallet (GoPay)',
            'qris' => 'QRIS'
        ];

        return $methods[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    // Methods
    public function markAsSuccess(): bool
    {
        return $this->update([
            'status' => 'success',
            'paid_at' => now()
        ]);
    }

    public function markAsFailed(): bool
    {
        return $this->update(['status' => 'failed']);
    }
}
