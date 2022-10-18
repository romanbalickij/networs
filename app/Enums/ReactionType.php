<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ReactionType extends Enum
{
    const MODEL_POST    = 'post';
    const MODEL_MESSAGE = 'message';

    const TYPE_FIRE  = 'fire';
    const TYPE_HEART = 'heart';

}
