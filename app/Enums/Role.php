<?php

namespace App\Enums;

use App\Traits\Enum\Options;

enum Role: string 
{
    use options;

    case ADMIN = 'admin';
    case USER = 'user';


    /**
     * Returns the human-readable name for the competition type.
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            self::USER => 'User',
        };
    }
}