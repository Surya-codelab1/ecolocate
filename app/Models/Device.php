<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model_name',
        'category',
        'description',
        'materials',
        'harmful_components',
        'estimated_recycling_value',
        'eco_credits',
        'recycling_information',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'estimated_recycling_value' => 'decimal:2',
            'eco_credits' => 'integer',
        ];
    }

    public function pickupRequests(): HasMany
    {
        return $this->hasMany(PickupRequest::class);
    }

    public function ecoCreditTransactions(): HasMany
    {
        return $this->hasMany(EcoCreditTransaction::class);
    }
}