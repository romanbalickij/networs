<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class InvoiceType extends Enum
{
    const SUBSCRIPTION_PAYMENT          = 'subscription_payment';
    const DONATION                      = 'donation';
    const PPV                           = 'ppv';
    const REFERRAL_SUBSCRIPTION_PAYMENT = 'referral_subscription_payment';
    const REFERRAL_DONATION             = 'referral_donation';
    const REFERRAL_PPV                  = 'referral_ppv';
    const COMMISSION                    = 'commission';
    const WITHDRAWAL                    = 'withdrawal';

    const DIRECTION_DEBIT               = 'debit';
    const DIRECTION_CREDIT              = 'credit';

    const RECEIVED_PAYMENT  = 'receive_payment';
    const MAKE_PAYMENT      = 'make_payment';
    const RECEIVE_PLATFORM  = 'receive_platform';
    const RECEIVED_REFERRAL = 'receive_referral';
    const MAKE_WITHDRAWAL   = 'make_withdrawal';

    const STATUS_FAILED  = 'failed';
    const STATUS_SUCCESS = 'success';
    const STATUS_PENDING = 'pending';
    const STATUS_DEFAULT = null;

}
