<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $token
 * @property Carbon $expires_at
 * @property Carbon|null $redeemed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'expires_at', 'redeeemed_at'])]
#[Hidden(['token'])]
class Invitation extends Model
{
    use Prunable;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'redeemed_at' => 'datetime',
        ];
    }

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::where('expires_at', '<', now());
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    protected static function booted(): void
    {
        static::creating(function (Invitation $invitation) {
            $invitation->token = $invitation->token ?? Str::random(32);
        });
    }

    public function hasExpired()
    {
        return Carbon::now() > $this->expires_at;
    }

    public function isRedeemed(): bool
    {
        return !is_null($this->redeemed_at);
    }

    public function url()
    {
        return route('invitation.redeem', ['invitation' => $this]);
    }
}