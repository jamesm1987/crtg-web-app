<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $api_id
 * @property string $country
 * @property string $type
 * @property boolean $track_scorers
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

 #[Fillable(['name', 'api_id', 'country', 'type', 'track_scorers'])]
class Competition extends Model
{
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'track_scorers' => 'boolean',
        ];
    }
}
