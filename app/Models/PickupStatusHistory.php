<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupStatusHistory extends Model
{
    use HasFactory;

    public $timestamps = false; // only created_at is used (no updated_at column)

    protected $fillable = [
        'pickup_request_id',
        'status',
        'changed_by',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function pickupRequest(): BelongsTo
    {
        return $this->belongsTo(PickupRequest::class);
    }
}