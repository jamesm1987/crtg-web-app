<?php

namespace App\Enums;

use App\Traits\Enum\Options;

enum CompetitionType: string
{
    use options;
    
    case LEAGUE = 'league';
    case DOMESTIC_CUP = 'domestic_cup';
    case EUROPEAN_CUP = 'european_cup';

    /**
     * Returns the human-readable name for the competition type.
     */
    public function label(): string
    {
        return match($this) {
            self::LEAGUE => 'League',
            self::DOMESTIC_CUP => 'Domestic Cup',
            self::EUROPEAN_CUP => 'European Cup',
        };
    }
}