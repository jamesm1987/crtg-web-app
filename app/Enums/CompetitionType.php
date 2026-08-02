<?php

namespace App\Enums;

use App\Traits\Enum\Options;

enum CompetitionType: string
{
    use options;
    
    case League = 'league';
    case Domestic_Cup = 'domestic_cup';
    case European_Cup = 'european_cup';

    /**
     * Returns the human-readable name for the competition type.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::League => 'League',
            self::Domestic_Cup => 'Domestic Cup',
            self::European_Cup => 'European Cup',
        };
    }
}