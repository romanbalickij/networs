<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ChatType extends Enum
{
    const CONTENT_CREATOR = 'content_creator';
    const ADMIN           = 'admin';

}
