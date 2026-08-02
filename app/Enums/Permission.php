<?php

namespace App\Enums;

use App\Traits\Enum\Options;

enum Permission: string 
{
    use options;

    case Admin = 'admin';
    case User = 'user';


    /**
     * Returns the human-readable name for the competition type.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            self::User => 'User',
        };
    }
}