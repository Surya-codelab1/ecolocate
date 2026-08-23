<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Relationships ─────────────────────────

    public function facility(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function facilityRequests(): HasMany
    {
        return $this->hasMany(FacilityRequest::class);
    }

    public function pickupRequests(): HasMany
    {
        return $this->hasMany(PickupRequest::class);
    }

    public function deviceRequests(): HasMany
    {
        return $this->hasMany(DeviceRequest::class);
    }

    public function ecoCreditTransactions(): HasMany
    {
        return $this->hasMany(EcoCreditTransaction::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ── Helpers ────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFacility(): bool
    {
        return $this->role === 'facility';
    }

    public function ecoCreditBalance(): int
    {
        return $this->ecoCreditTransactions()->sum('credits');
    }
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
