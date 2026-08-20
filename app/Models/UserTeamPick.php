<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property int $user_id
 * @property int $competition_id
 * @property int $team_id
 * @property Carbon|null $active_from
 * @property Carbon|null $active_to
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

#[Table('user_team_picks')]
#[Fillable(['user_id', 'competition_id', 'team_id', 'active_from', 'active_to'])]
class UserTeamPick extends Model
{
    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'active_from' => 'datetime',
            'active_to' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('active_to');
    }

    public function isActive(): bool
    {
        return is_null($this->active_to);
    }

    public function calculateEarnedPoints(): int
    {
        return TeamPointsLedger::query()
            ->where('team_id', $this->team_id)
            ->when($this->active_from, fn($q) => $q->where('earned_at', '>=', $this->active_from))
            ->when($this->active_to, fn($q) => $q->where('earned_at', '<', $this->active_to))
            ->sum('points');
    }
}
