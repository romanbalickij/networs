<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WithdrawType extends Enum
{
    const CREDIT_CARD       = 'credit_card';
    const CRYPTOCURRENCY    = 'crypto';

    const CRYPTO_TYPE_TRON     = 'tron';     // TRC-20
    const CRYPTO_TYPE_ETHEREUM = 'ethereum'; //ERC-20

    const CONFIRMED_BLOC_TRON      = 20;
    const CONFIRMED_BLOCK_ETHEREUM = 10;
}
