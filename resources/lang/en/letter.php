<?php


return [

    'account' => [
       'subject'        => 'Your account was verified',
       'text_1'         => 'Go to',
       'text_2'         => "You currently have :count unread notifications.",
       'text_button_1'  => 'REVIEW statistics',
       'text_button_2'  => 'VIEW ALL NOTIFICATIONS',
       'text_button_3'  => 'Manage my email settings',
       'text_button_4'  => 'Get help',
       'thank'          => 'Thank you,'
    ],

    'newInvoiceNotify' => [
        'subject'         => 'You’ve been charged $:total',
        'subject_2'       => 'You just received $:total',
        'text_1'          => 'Thank you for using',
        'text_2'          => 'A new :direction invoice (total of $:sum) has been issued for your account.',
        'button_download' => 'Download',
        'button_support'  => 'CONTACT SUPPORT',
        'button_invoices' => 'View all invoices',
        'button_settings' => 'Manage my email settings',
        'button_help'     => 'Get help',
        'thank'           => 'Thank you,'

    ],

    'promotion' => [
        'subject'         => 'Platform announcement',
        'button_thefans'  => 'Go to thefans',
        'button_settings' => 'Manage my email settings',
        'button_help'     => 'Get help',
        'thank'           => 'Thank you,'
    ],

    'payment-failed' => [
      'subject' => 'Failed payment attempt',
      'text_1'  => 'Unfortunately, we weren’t able to charge you $:sum for your :typePayment, message to',
      'text_2'  => 'unlocking from your card ending with' ,
      'text_3'  => 'Please update your payment methods.',
      'text_4'  => 'Hello!',
      'button_methods' => 'My payment methods',
      'button_help'    => 'Get help',
        'thank'        => 'Thank you,'
    ],

    'subscription-prolong' => [
        'subject' => 'Your auto-prolonged subscription has been suspended',
        'text_1' => 'Unfortunately, we weren’t able to charge you $:sum for your subscription to',
        'text_2' => 'from your card ending with ',
        'text_3' => 'Please update your payment methods.',
        'text_4' => 'Hello!',
        'button_methods' => 'My payment methods',
        'button_help' => 'Get help',
        'thank'       => 'Thank you,'
    ],

    'subscription-prolong-cancel' => [
        'subject' => 'Your auto-prolonged subscription has been cancelled',
        'text_1'  => 'Hello!',
        'text_2'  => 'Unfortunately, we weren’t able to charge you $:sum for your subscription to',
        'text_3'  => 'from your card ending with',
        'text_4'  => 'Your subscription has been cancelled. Please update your payment methods.',
        'button_methods' => 'My payment methods',
        'button_help'    => 'Get help',
        'thank'          => 'Thank you,'
    ],

    'notify-react-post-or-message' => [
      'text_1' => 'reacted to your',
      'text_2' => 'message',
      'text_3' => 'post',
      'text_4' => 'You currently have :count unread notifications.',
      'button_statistics'   => 'REVIEW statistics',
      'button_notification' => 'VIEW ALL NOTIFICATIONS',
      'button_settings'     => 'Manage my email settings',
      'button_help'         => 'Get help',
        'thank'             => 'Thank you,'
    ],

    'notify-subscription' => [
        'subject' => ':text has just subscribed to your account',
        'text_1' => 'subscribed to you',
        'text_2' => 'You currently have :count unread notifications.',
        'button_statistics'   => 'REVIEW statistics',
        'button_notification' => 'VIEW ALL NOTIFICATIONS',
        'button_settings'     => 'Manage my email settings',
        'button_help'         => 'Get help',
        'thank'               => 'Thank you,'
    ],

    'notify-comment'  => [
        'subject' => ':text commented on your post',
        'text_1' => 'commented on your publication',
        'text_2' => 'You currently have :count unread notifications.',
        'button_statistics'   => 'REVIEW statistics',
        'button_notification' => 'VIEW ALL NOTIFICATIONS',
        'button_settings'     => 'Manage my email settings',
        'button_help'         => 'Get help',
        'thank'               => 'Thank you,'

    ],

    'notify-account-blocked' => [
        'subject' => 'Your account was blocked',
        'text_1' => 'You currently have :count unread notifications.',
        'button_support'      => 'Contact support' ,
        'button_statistics'   => 'REVIEW statistics' ,
        'button_notification' => 'VIEW ALL NOTIFICATIONS' ,
        'button_terms'        => 'Terms of use' ,
        'button_settings'     => 'Manage my email settings' ,
        'button_help'         => 'Get help' ,
        'thank'               => 'Thank you,'
    ],

    'notify-comment-moderated' => [
      'subject' => 'One of your comments was removed by the moderator',
      'text_1' => 'Moderated',
      'text_2' => 'You currently have :count unread notifications.',
        'button_support'      => 'Contact support' ,
        'button_statistics'   => 'REVIEW statistics' ,
        'button_notification' => 'VIEW ALL NOTIFICATIONS' ,
        'button_terms'        => 'Terms of use' ,
        'button_settings'     => 'Manage my email settings' ,
        'button_help'         => 'Get help' ,
        'thank'               => 'Thank you,'
    ],

    'notify-unread-message'  => [
        'subject' => 'You have :text unread messages',
        'text_1' => 'You currently have :count unread messages.',
        'button_messages'     => 'VIEW ALL messages' ,
        'button_settings'     => 'Manage my email settings' ,
        'button_help'         => 'Get help' ,
        'thank'               => 'Thank you,'
    ],

    'verify' =>[
        'subject' => 'Verify Email Address'
    ],

    'password-reset' =>[
        'hello'   => 'Hello!',
        'subject' => 'Password change request'
    ]


];
