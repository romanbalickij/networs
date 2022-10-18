<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PageType extends Enum
{
    const DEFAULT = 'default';
    const CUSTOM  = 'custom';

    const LANDING_HEADER  = 'landing_header';
    const LANDING_FOOTER  = 'landing_footer';
    const LANDING_CONTENT = 'landing_content';
    const LANDING_KPI     = 'landing_kpi';
    const LANDING_CREATOR = 'creator_landing';

    const CONTENT_PAGE_POLICY   = 'privacy_policy';
    const CONTENT_PAGE_FAQ      = 'faq';
    const CONTENT_PAGE_SUPPORT  = 'support';
    const CONTENT_PAGE_TERM_USE = 'terms_og_use';

    const CONTENT_PAGE_400 = 'error_400';
    const CONTENT_PAGE_500 = 'error_500';

    const LETTER_VERIFY_EMAIL      = 'verify_email';
    const LETTER_PASSWORD_CHANGE   = 'password_change';
    const LETTER_PROMOTION         = 'promotion';
    const LETTER_NEW_INVOICE       = 'new_invoice';
    const LETTER_COMMENT_MODERATED = 'comment_moderated';
    const LETTER_UNREAD_MESSAGE    = 'unread_message';
    const LETTER_NEW_SUBSCRIBED    = 'new_subscribed';
    const LETTER_NEW_COMMENT       = 'new_comment';
    const LETTER_ACCOUNT_VERIFY    = 'account_verify';
    const LETTER_ACCOUNT_BLOCKED   = 'account_blocked';
    const LETTER_REACTION          = 'reaction';
    const LETTER_FAILED_PAYMENT    = 'failed_payment';
    const LETTER_SUBSCRIPTION_PROLONGED          = 'subscription_prolonged';
    const LETTER_SUBSCRIPTION_PROLONGED_CANCELED = 'subscription_prolonged_canceled';
}
