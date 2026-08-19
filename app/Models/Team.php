<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property int $api_id
 * @property int $competition_id
 * @property int|null $price
 * @property string|null $logo_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

#[Fillable(['name', 'display_name', 'api_id', 'competition_id', 'price', 'logo_url'])]
class Team extends Model
{
    use SoftDeletes;

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class, 'competition_id');
    }

    public function calculateEarnedPoints(): int
    {
        return TeamPointsLedger::query()
            ->where('team_id', $this->id)
            ->sum('points');
    }
}
