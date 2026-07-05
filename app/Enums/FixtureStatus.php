<?php

namespace App\Enums;

use App\Traits\Enum\Options;

enum FixtureStatus: string 
{
    use options;

    case NOT_STARTED = 'NS';
    case FULL_TIME   = 'FT';


    /**
     * Returns the human-readable name for the competition type.
     */
    public function label(): string
    {
        return match($this) {
            self::NOT_STARTED => 'Not Started',
            self::FULL_TIME => 'Full Time',
        };
    }
}