<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * @property int $id
 * @property int $api_id
 * @property int $home_team_id
 * @property int $away_team_id
 * @property int $competition_id
 * @property int|null $home_team_score
 * @property int|null $away_team_score
 * @property Carbon|null $kick_off_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

 #[Fillable(['api_id', 'home_team_id', 'away_team_id', 'competition_id', 'home_team_score', 'away_team_score', 'kick_off_at'])]
class Fixture extends Model
{
    use SoftDeletes;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'kick_off_at' => 'datetime',
        ];
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }    

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->homeTeam?->name} vs {$this->awayTeam?->name}",
        );
    }
}
