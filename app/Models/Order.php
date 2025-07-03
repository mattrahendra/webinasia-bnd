<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'template_id',
        'domain_name',
        'domain_extension',
        'template_price',
        'domain_price',
        'total_price',
        'status',
        'customer_data'
    ];

    protected $casts = [
        'template_price' => 'decimal:2',
        'domain_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'customer_data' => 'array'
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getFullDomainAttribute(): string
    {
        return $this->domain_name . '.' . $this->domain_extension;
    }

    public function getCustomerNameAttribute(): ?string
    {
        return $this->customer_data['name'] ?? null;
    }

    public function getCustomerEmailAttribute(): ?string
    {
        return $this->customer_data['email'] ?? null;
    }

    public function getLatestPaymentAttribute(): ?Payment
    {
        return $this->payments()->latest()->first();
    }

    public function getIsPaidAttribute(): bool
    {
        return in_array($this->status, ['paid', 'processing', 'completed']);
    }

    public function markAsPaid(): bool
    {
        return $this->update(['status' => 'paid']);
    }

    public function markAsCompleted(): bool
    {
        return $this->update(['status' => 'completed']);
    }

    public function cancel(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }
}
