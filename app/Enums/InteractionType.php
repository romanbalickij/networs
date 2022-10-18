<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class InteractionType extends Enum
{

    const MODEL_CHAT          = 'chat';
    const MODEL_SUBSCRIPTION  = 'subscription';
    const MODEL_INVOICE       = 'invoice';
    const MODEL_COMMENT       = 'comment';
    const MODEL_DONATE        = 'donation';
    const MODEL_POST          = 'post';
    const MODEL_MESSAGE       = 'message';
    const MODEL_USER          = 'user';
    const MODEL_FILE          = 'attachment';


    const TYPE_CHAT_CREATION             = 'chat_creation';
    const TYPE_BOOKMARKING               = 'bookmarking';
    const TYPE_COMMENTING                = 'commenting';
    const TYPE_REACTION                  = 'reaction';
    const TYPE_RESPONSE_COMMENT          = 'responses_comment';
    const TYPE_SUBSCRIPTION_CANCELLATION = 'subscription_cancellation';
    const TYPE_SEND_DONATION             = 'sent_donation';
    const TYPE_RECEIVED_DONATION         = 'received_donation';
    const TYPE_SUBSCRIBER_CANCELLATION   = 'subscriber_cancellation';
    const TYPE_SUBSCRIPTION              = 'subscription';
    const TYPE_INVOICE_CREATION          = 'invoice_creation';
    const TYPE_INVOICE_BILLING           = 'invoice_billing';
    const TYPE_ACCOUNT_VERIFICATION      = 'account_verification';
    const TYPE_ACCOUNT_BLOCKING          = 'account_blocking';
    const TYPE_ACCOUNT_UNBLOCKING        = 'account_unblocking';
    const TYPE_NEW_SUBSCRIBER            = 'new_subscriber';

}
