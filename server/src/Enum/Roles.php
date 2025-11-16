<?php

namespace App\Enum;

enum Roles: string
{
    case ADMIN = 'ROLE_ADMIN';
    case GAME_OWNER = 'ROLE_GAME_OWNER';
    case PLAYER = 'ROLE_PLAYER';
}
