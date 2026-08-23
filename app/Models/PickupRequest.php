<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PickupRequest extends Model
{
    use HasFactory;

    // Step-by-step status flow. 'Cancelled' is a separate branch, not part of this chain.
    public const STATUS_FLOW = [
        'Requested' => 'Accepted',
        'Accepted'  => 'Scheduled',
        'Scheduled' => 'Collected',
        'Collected' => 'Completed',
    ];

    protected $fillable = [
        'user_id',
        'facility_id',
        'device_id',
        'pickup_address',
        'preferred_date',
        'preferred_time',
        'additional_note',
        'status',
        'certificate_path',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(PickupStatusHistory::class);
    }

    public function ecoCreditTransactions(): HasMany
    {
        return $this->hasMany(EcoCreditTransaction::class);
    }

    // ── Status Helpers ─────────────────────────

    public function nextStatus(): ?string
    {
        return self::STATUS_FLOW[$this->status] ?? null;
    }

    public function isFinal(): bool
    {
        return in_array($this->status, ['Completed', 'Cancelled'], true);
    }
}