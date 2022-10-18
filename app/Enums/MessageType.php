<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MessageType extends Enum
{
   const ADMIN_ENTERED = 'admin_entered';
   const ADMIN_LEFT    = 'admin_left';
   const USER_DONATED  = 'user_donated';
   CONST DEFAULT       =  null;
}
