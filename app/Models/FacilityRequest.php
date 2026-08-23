<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacilityRequest extends Model
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
        'accepted_ewaste',
        'pickup_availability',
        'working_hours',
        'status',
        'admin_note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}