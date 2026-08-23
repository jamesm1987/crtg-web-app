<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $player_name
 * @property int $api_id
 * @property int $competition_id
 * @property int $team_id
 * @property int $goals
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

#[Fillable(['player_name', 'api_id', 'competition_id', 'team_id', 'goals'])]
class TopScorer extends Model
{
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
