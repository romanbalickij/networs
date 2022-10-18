<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ManagedAccountType extends Enum
{
    const MANAGED = 'managed';
    const OWNER   = 'owner';

}
