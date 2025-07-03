<?php

namespace App\Enums;

class RoleEnum
{
    public const ADMIN = 1;
    public const OFFICER = 2;
    public const REVIEWER = 3;
    public const DIRECTOR = 4;
    public const BOARD_MEMBER = 5;

     /**
     * Return roles as an associative array
     */
    public static function toArray(): array
    {
        return [
            self::ADMIN => 'Admin',
            self::OFFICER => 'Officer',
            self::REVIEWER => 'Reviewer',
            self::DIRECTOR => 'Director',
            self::BOARD_MEMBER => 'Board Member',
        ];
    }
}
