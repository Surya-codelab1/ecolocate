<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'facility_name',
        'contact_person',
        'email',
        'area',
        'full_address',
        'latitude',
        'longitude',
        'accepted_ewaste',
        'pickup_availability',
        'working_hours',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function pickupRequests(): HasMany
    {
        return $this->hasMany(PickupRequest::class);
    }
}