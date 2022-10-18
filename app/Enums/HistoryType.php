<?php

namespace App\Enums;

use App\Models\Post;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class HistoryType extends Enum
{
    const POST                  = 'post';
    const MESSAGE               = 'message';
    const SUBSCRIPTION          = 'subscription';
    const DONATION              = 'donation';
    const POST_SHOW_HISTORY     = 'postShowHistory';
    const POST_CLICK_HISTORY    = 'postClickthroughHistory';
    const POST_INTEREST_HISTORY = 'postInterestHistory';
    const COMMENT               = 'comment';
    const REFERRAL_LINK         = 'referralLink';

}
