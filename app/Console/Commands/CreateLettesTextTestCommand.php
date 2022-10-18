<?php

namespace App\Console\Commands;

use App\Enums\PageType;
use App\Models\InterfaceImage;
use App\Models\InterfaceText;
use App\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateLettesTextTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:text';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        Page::whereIn('key',[
            PageType::LETTER_VERIFY_EMAIL,
            PageType::LETTER_PASSWORD_CHANGE,
            PageType::LETTER_PROMOTION,
            PageType::LETTER_NEW_INVOICE,
            PageType::LETTER_COMMENT_MODERATED,
            PageType::LETTER_UNREAD_MESSAGE,
            PageType::LETTER_NEW_SUBSCRIBED,
            PageType::LETTER_NEW_COMMENT,
            PageType::LETTER_ACCOUNT_VERIFY,
            PageType::LETTER_ACCOUNT_BLOCKED,
            PageType::LETTER_REACTION,
            PageType::LETTER_FAILED_PAYMENT,
            PageType::LETTER_SUBSCRIPTION_PROLONGED,
            PageType::LETTER_SUBSCRIPTION_PROLONGED_CANCELED

        ])->get()->each(fn($page) => $page->delete());



        foreach ($this -> pages() as $page) {

            $pageModel = Page::updateOrCreate(['name' => $page['page']['name']],$page['page']);

            if(!isset($page['texts'])) {
                continue;
            }

            foreach ($page['texts'] as  $text) {

                $interface = InterfaceText::updateOrCreate(array_merge($text, ['page_id' =>  $pageModel->id]));

            }
        }


    }


    private function pages(): array {

        return [

            [
                'page' => [
                    'name' => 'Verify email',
                    'key' =>  PageType::LETTER_VERIFY_EMAIL,
                    'title' => ['en' => 'Verify email', 'ru' => 'Verify email'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => 'Пожалуйста, нажмите кнопку ниже, чтобы подтвердить свой адрес электронной почты', 'en'=> 'Please click the button below to verify your email address'], 'key' => 'header', 'length_limit' => 120],
                    ['name' => '', 'text' =>  ['ru' => "<p>Если кнопка не работает, скопируйте и вставьте следующую ссылку в адресную строку браузера.:</p>", 'en'=> "<p>If the button doesn't work, please copy paste the following link in your browser:</p>"], 'key' => 'footer', 'length_limit' => 120],
                    ['name' => '', 'text' =>  [
                        'ru' => "<p>если вы не создавали учетную запись, никаких дальнейших действий не требуется.</p>
        <p>Вы не сможете вывести деньги или сбросить пароль, если не подтвердите адрес электронной почты.</p>
        <p>Спасибо,<br>TheFans team.</p>",
                        'en'=> "<p>If you did not create an account, no further action is required.</p>
        <p>You won’t be able to withdraw money or reset your password unless you confirm the email address.</p>
        <p>Thank you,<br>TheFans team.</p>"
                    ], 'key' => 'body', 'length_limit' => 120],

                ],
            ],

            [
                'page' => [
                    'name' => 'RESET PASSWORD',
                    'key' =>  PageType::LETTER_PASSWORD_CHANGE,
                    'title' => ['en' => 'RESET PASSWORD', 'ru' => 'RESET PASSWORD'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => 'Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.', 'en'=> 'You are receiving this email because we received a password reset request for your account.'], 'key' => 'header', 'length_limit' => 120],
                    ['name' => '', 'text' =>  ['ru' => "<p>Если кнопка не работает, скопируйте и вставьте следующую ссылку в адресную строку браузера.:</p>", 'en'=> "<p>If the button doesn't work, please copy paste the following link in your browser:</p>"], 'key' => 'footer', 'length_limit' => 120],
                    ['name' => '', 'text' =>
                        ['ru' => "<p>Срок действия этой ссылки для сброса пароля истекает через 60 минут.</p>
    <p>Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.</p>
    <p>Спасибо,<br>TheFans team.</p>",
                        'en'=>  "<p>This password reset link will expire in 60 minutes</p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Thank you,<br>TheFans team.</p>", ], 'key' => 'body', 'length_limit' => 120],

              ],
            ],

            [
                'page' => [
                    'name' => 'PROMOTION',
                    'key' =>  PageType::LETTER_PROMOTION,
                    'title' => ['en' => 'PROMOTION', 'ru' => 'PROMOTION'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'NEW INVOICE',
                    'key' =>  PageType::LETTER_NEW_INVOICE,
                    'title' => ['en' => 'NEW INVOICE', 'ru' => 'NEW INVOICE'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => 'Если у вас есть вопросы, не стесняйтесь обращаться к нам за дополнительной информацией', 'en'=> 'If you have questions, don’t hesitate to contact us for more information'], 'key' => 'header', 'length_limit' => 120],
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'Comment',
                    'key' =>  PageType::LETTER_COMMENT_MODERATED,
                    'title' => ['en' => 'Comment', 'ru' => 'Comment'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => 'Один из ваших комментариев был удален модератором. Пожалуйста, ознакомьтесь с нашими условиями использования и, если у вас есть дополнительные вопросы, обратитесь в нашу службу поддержки.', 'en'=> 'One of your comments was removed by the moderator. Please review our terms of use and if you have further questions, reach out to our support'], 'key' => 'header', 'length_limit' => 120],
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'Unread message',
                    'key' =>  PageType::LETTER_UNREAD_MESSAGE,
                    'title' => ['en' => 'unread message', 'ru' => 'unread message'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => 'Похоже, вы давно не отвечаете на сообщения. Мы подготовили сводку ваших последних пропущенных разговоров!', 'en'=> 'Looks like you haven’t been responding to messages in a while. We prepared a summary of your latest missed conversations!'], 'key' => 'header', 'length_limit' => 120],
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'new subscribed',
                    'key' =>  PageType::LETTER_NEW_SUBSCRIBED,
                    'title' => ['en' => 'new subscribed', 'ru' => 'new subscribed'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'new comment',
                    'key' =>  PageType::LETTER_NEW_COMMENT,
                    'title' => ['en' => 'new comment', 'ru' => 'new comment'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'account verify',
                    'key' =>  PageType::LETTER_ACCOUNT_VERIFY,
                    'title' => ['en' => 'account verify', 'ru' => 'account verify'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => "Ваша учетная запись успешно проверена. Спасибо, что выбрали нас!", 'en'=> "Your account was successfully verified. Thank you for choosing us!"], 'key' => 'header', 'length_limit' => 120],
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'account blocked',
                    'key' =>  PageType::LETTER_ACCOUNT_BLOCKED,
                    'title' => ['en' => 'account blocked', 'ru' => 'account blocked'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => "С сожалением сообщаем вам, что ваша учетная запись была заблокирована. Если вы хотите получить дополнительную информацию или обжаловать это решение, свяжитесь с нами.", 'en'=> "We regret to inform you that your account was blocked. Please reach out to us if you’d like to get further information or appeal this decision. "], 'key' => 'header', 'length_limit' => 120],
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'reaction',
                    'key' =>  PageType::LETTER_REACTION,
                    'title' => ['en' => 'reaction', 'ru' => 'reaction'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => "<p>Вы получили это письмо, потому что подписались на такого рода уведомления.</p>", 'en'=> "<p>You received this email because you subscribed to this kind of notifications.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'failed payment',
                    'key' =>  PageType::LETTER_FAILED_PAYMENT,
                    'title' => ['en' => 'failed payment', 'ru' => 'failed payment'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => "<p>Если у вас возникли проблемы, не стесняйтесь обращаться в нашу службу поддержки, мы будем рады вам помочь.</p>", 'en'=> "<p>If you’re having trouble, don’t hesitate to contact our support team, we’re eager to help you.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'subscription prolonged',
                    'key' =>  PageType::LETTER_SUBSCRIPTION_PROLONGED,
                    'title' => ['en' => 'subscription prolonged', 'ru' => 'subscription prolonged'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => '<p>Будет еще $number попытки продлить подписку. Если вы не обновите свои способы оплаты до этого момента, ваша подписка будет отменена.</p>', 'en'=> '<p>There will be $number more attempts to renew your subscription. If you don’t update your payment methods until then, your subscription will be cancelled.</p>'], 'key' => 'body', 'length_limit' => 120],
                    ['name' => '', 'text' =>  ['ru' => "<p>Если у вас возникли проблемы, не стесняйтесь обращаться в нашу службу поддержки, мы будем рады вам помочь.</p>", 'en'=> "<p>If you’re having trouble, don’t hesitate to contact our support team, we’re eager to help you.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

            [
                'page' => [
                    'name' => 'subscription prolonged canceled',
                    'key' =>  PageType::LETTER_SUBSCRIPTION_PROLONGED_CANCELED,
                    'title' => ['en' => 'subscription prolonged canceled', 'ru' => 'subscription prolonged canceled'],
                    'type' => PageType::CUSTOM,
                ],
                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => "<p>Если у вас возникли проблемы, не стесняйтесь обращаться в нашу службу поддержки, мы будем рады вам помочь.</p>", 'en'=> "<p>If you’re having trouble, don’t hesitate to contact our support team, we’re eager to help you.</p>"], 'key' => 'footer', 'length_limit' => 120],
                ],
            ],

        ];
    }
}
