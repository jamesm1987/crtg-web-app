<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property int $team_id
 * @property int $competition_id
 * @property int|null $fixture_id
 * @property int $scoring_rule_id
 * @property int $points
 * @property string $source
 * @property string $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

#[Table('team_points_ledger')]
#[Fillable(['team_id', 'competition_id', 'fixture_id', 'scoring_rule_id', 'points', 'source', 'notes'])]
class teamPointsLedger extends Model
{


     /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'points' => 'integer',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }

    public function scoringRule(): BelongsTo
    {
        return $this->belongsTo(ScoringRule::class);
    }
}