<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/*
* @property int $season
* @property int $budget
* @property int $entry_fee
* @property int $teams_per_league
* @property Carbon|null $transfer_window_open_at
* @property Carbon|null $transfer_window_close_at
*/
#[Fillable(['season', 'budget', 'entry_fee', 'teams_per_league', 'transfer_window_open_at', 'transfer_window_close_at'])]
class Setting extends Model
{

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'transfer_window_open_at' => 'datetime',
            'transfer_window_close_at' => 'datetime',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('app_settings', function () {
            return self::first();
        });
    
        return $settings?->{$key} ?? $default;
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('app_settings'));
    }
}
