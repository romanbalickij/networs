<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TrackFnType extends Enum
{
    const SIGNUP    = 'signup';
    const SUBSCRIBE = 'subscribe';
    const STOP      = 'stop';
    const REBILL    = 'rebill';
    const CONFIRM   = 'confirm';
    const DONATE    = 'donate';
    const PPV       = 'ppv';
}
