<?php

namespace App\Console\Commands;

use App\Enums\HistoryType;
use App\Enums\NotificationType;
use App\Models\Comment;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\AccountBlockedNotification;
use App\Notifications\AccountMailNotification;
use App\Notifications\CommentMailNotification;
use App\Notifications\CommentModeratedMailNotification;
use App\Notifications\InvoiceNotification;
use App\Notifications\PaymentFailedMailNotification;
use App\Notifications\PromotionNotification;
use App\Notifications\ReactionMailNotification;
use App\Notifications\SubscribeMailNotification;
use App\Notifications\SubscriptionAutoProlongedCancelMailNotification;
use App\Notifications\SubscriptionAutoProlongedMailNotification;
use App\Notifications\TestPasswordRessetMailNotification;
use App\Notifications\TestVerifyMailNotification;
use App\Notifications\UnreadMessageMailNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class SendTestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send {user=1}  {mail=newInvoiceNotify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a marketing email to a user';


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

        $userId    = $this->argument('user');

        $nameEmail = $this->argument('mail');

        $user = User::find($userId);

        $locale = $user->locale;


        switch ($nameEmail) {
            case 'newInvoiceNotify':

                $invoice = Invoice::where('user_id', $userId)->first() ?? Invoice::first();

                $user->notify((new InvoiceNotification($invoice))->locale($locale));

                break;
            case 'promotionNotify':

                $mes =  Message::where('user_id', $userId)->first() ?? Message::first();

                $user->notify((new PromotionNotification($mes))->locale($locale));
                break;
            case 'payment-failed':

                $card = PaymentMethod::where('user_id', $userId)->first() ?? PaymentMethod::first();
                $sum = 20;
                $type = HistoryType::POST;

                $user->notify((new PaymentFailedMailNotification($card, $sum, $type, $user))->locale($locale));

                break;
            case 'subscription-prolong':
                $syb =  Subscription::where('user_id', $userId)->first() ?? Subscription::first();

                $user->notify((new SubscriptionAutoProlongedMailNotification($syb))->locale($locale));

                break;
            case 'subscription-prolong-cancel':
                $syb =  Subscription::where('user_id', $userId)->first() ?? Subscription::first();

                $user->notify(

                    (new SubscriptionAutoProlongedCancelMailNotification($syb))->locale($locale)
                );

                break;
            case 'notify-react-post-or-message':

                $notification = Notification::where('entity_type', NotificationType::REACTION_MESSAGE)->where('user_id', $userId)->first();

                $user->notify((new ReactionMailNotification($notification))->locale($locale));

                break;
            case 'notify-subscription':

                $subs = Subscription::where('creator_id', $userId)->first() ?? Subscription::first();

                $user->notify((new SubscribeMailNotification($subs))->locale($locale));

                break;
            case 'notify-comment':

                $comment = Comment::first();

                $user->notify((new CommentMailNotification($comment, $user))->locale($locale));

                break;
            case 'notify-account':

                $user->notify((new AccountMailNotification($user))->locale($locale));

                break;
            case 'notify-account-blocked':

                $user->notify((new AccountBlockedNotification($user))->locale($locale));

                break;
            case 'notify-comment-moderated':

                $comment = Comment::where('user_id', $userId)->first() ?? Comment::first();

                $user->notify((new CommentModeratedMailNotification($comment))->locale($locale));

                break;
            case 'notify-unread-message':

                $chats =  $user->chats->pluck('id');

                $messages = Message::query()
                    ->whereIn('chat_id', $chats->toArray())
                    ->where('user_id', '!=', $userId)
                    ->notYetSentToMail()
                    ->unread()
                    ->get();

                $user->notify((new UnreadMessageMailNotification($messages, $user))->locale($locale));

                break;
            case 2:
                echo "i равно 2";
                break;
        }
    }
}
