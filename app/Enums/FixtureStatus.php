<?php

namespace App\Enums;

use App\Traits\Enum\Options;

enum FixtureStatus: string 
{
    use options;

    case Not_Started = 'NS';
    case Full_Time   = 'FT';


    /**
     * Returns the human-readable name for the competition type.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Not_Started => 'Not Started',
            self::Full_Time => 'Full Time',
        };
    }
}