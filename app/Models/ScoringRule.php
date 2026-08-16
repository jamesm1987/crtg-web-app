<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $category
 * @property string $code
 * @property string $label
 * @property int $points
 * @property int|null $threshold
 * @property boolean $is_active
 * @property Carbon|null $deleted_at 
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

#[Fillable(['category', 'code', 'label', 'points', 'threshold', 'is_active'])]
class ScoringRule extends Model
{
    use SoftDeletes;

    public function competitions(): HasMany
    {
        return $this->hasMany(Competition::class, 'trophy_scoring_rule_id');
    }
}
