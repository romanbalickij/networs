<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PostType extends Enum
{
    const PRIVATE = 'private';
    const PUBLIC  = 'public';

    //type sort
    const SORT_HOT                  = 'hot';
    const SORT_TOP_MONTH            = 'top_month';
    const SORT_TOP                  = 'top';
    const SORT_MOST_DISCUSSED_MONTH = 'most_discussed_month';
    const SORT_MOST_DISCUSSED       = 'most_discussed';
    const SORT_NEW                  = 'new';
    const SUBSCRIPTIONS             = 'subscriptions';
}
