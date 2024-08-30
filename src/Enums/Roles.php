<?php

namespace dorukyy\loginx\Enums;

class Roles
{
    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const MODERATOR = 3;
    const USER = 4;

    public static function data(): array
    {
        return [
            self::SUPER_ADMIN => 1,
            self::ADMIN => 2,
            self::MODERATOR => 3,
            self::USER => 4
        ];
    }

}
