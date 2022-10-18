<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class NotificationType extends Enum
{
    const REACTION_POST     = 'reaction_post';
    const REACTION_MESSAGE  = 'reaction_message';
    const SUBSCRIPTION      = 'subscription';
    const DONATION          = 'donation';
    const UNREAD_MESSAGES   = 'unread_messages';
    const COMMENT           = 'comment';
    const ENFORCEMENT       = 'enforcement'; //todo not use
    const ACCOUNT_VERIFIED  = 'account_verified';
    const ACCOUNT_BLOCKED   = 'account_blocked';
    const ACCOUNT_UNBLOCKED = 'account_unblocked';
    const ACCOUNT_COMMENT_MODERATED = 'comment_moderated';
}
