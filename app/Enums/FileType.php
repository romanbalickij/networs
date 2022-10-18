<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class FileType extends Enum
{
   const MODEL_POST    = 'post';
   const MODEL_MESSAGE = 'message';

   const TYPE_IMAGE    = 'image';
   const TYPE_VIDEO    = 'video';
   const TYPE_OTHER    = 'other';

}
