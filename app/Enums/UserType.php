<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserType extends Enum
{
    const USER     = 'user';
    const ADMIN    = 'admin';

    const ACTIVE   = 'active';
    const BUSY     = 'busy';
    const INACTIVE = 'inactive';
}
