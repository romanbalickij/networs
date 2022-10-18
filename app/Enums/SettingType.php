<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SettingType extends Enum
{
    const THEME                     = 'theme';
    const PAGE_POST_VISIBILITY      = 'page_post_visibility';
    const DISPLAY_ONLINE_STATUS     = 'display_online_status';
    const DISPLAY_SUBSCRIBER_NUMBER = 'display_subscriber_number';
    const AUTO_PROLONG_SUBSCRIPTION = 'auto_prolong_subscription';
    const REACTION                  = 'reaction';
    const SUBSCRIPTION              = 'subscription';
    const DONATION                  = 'donation';
    const UNREAD_MESSAGE            = 'unread_message';
    const COMMENT_RESPONSE          = 'comment_response';
    const INVOICE                   = 'invoice';
    const PROMOTION                 = 'promotion';
}
