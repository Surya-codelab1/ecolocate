<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function facilityRequests(): HasMany
    {
        return $this->hasMany(FacilityRequest::class);
    }
}