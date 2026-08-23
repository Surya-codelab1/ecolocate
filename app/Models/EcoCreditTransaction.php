<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoCreditTransaction extends Model
{
    use HasFactory;

    public $timestamps = false; // only created_at column exists

    protected $fillable = [
        'user_id',
        'pickup_request_id',
        'device_id',
        'credits',
        'type',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'credits' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pickupRequest(): BelongsTo
    {
        return $this->belongsTo(PickupRequest::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}