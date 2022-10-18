<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BookmarkType extends Enum
{
    const MODEL_POST       = 'post';
    const MODEL_MESSAGE    = 'message';
    const MODEL_ATTACHMENT = 'attachment';
    const MODEL_USER       = 'user';
}
