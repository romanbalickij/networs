<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class EventType extends Enum
{

   const EVENT_MARK_MESSAGE_READ = 'mark_message_read'; // @deprecated
//   const EVENT_NOTIFICATION_READ = 'mark_notification_read'; @deprecated
   const EVENT_MESSAGE           = 'message_send';
   const EVENT_NOTIFICATION      = 'notification_send';
   const EVENT_REACTION          = 'reaction';
   const EVENT_REACTION_MESSAGE  = 'reaction_message';
   const EVENT_FROM_FRONTEND_POST_INTEREST     = 'post_interest';
   const EVENT_FROM_FRONTEND_POST_CLICK        = 'post_click';
   const EVENT_FINISHED_LOADING_PICTURE        = 'finished_loading_picture';

    const EVENT_FROM_FRONTEND_SEND_TEXT_MESSAGE      = 'messageSend';
    const EVENT_FROM_FRONTEND_SEND_MESSAGE_READ      = 'messageRead';
    const EVENT_FROM_FRONTEND_SEND_NOTIFICATION_READ = 'notificationRead';
    const EVENT_FROM_FRONTEND_CLICK_POST             = 'clickPost';
    const EVENT_FROM_FRONTEND_INTEREST_POST          = 'interestPost';
}
