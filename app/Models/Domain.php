<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'extension',
        'price',
        'renewal_price',
        'status',
        'user_id',
        'reserved_until',
        'expires_at',
        'godaddy_data'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'renewal_price' => 'decimal:2',
        'reserved_until' => 'datetime',
        'expires_at' => 'datetime',
        'godaddy_data' => 'array'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }

    public function scopeExpiredReservations($query)
    {
        return $query->where('status', 'reserved')
                    ->where('reserved_until', '<', now());
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->reserved_until && $this->reserved_until < now();
    }

    // Methods
    public function releaseReservation(): bool
    {
        return $this->update([
            'status' => 'available',
            'user_id' => null,
            'reserved_until' => null
        ]);
    }
}
