<?php

namespace App\Console\Commands;

use App\Enums\NotificationType;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Notifications\UnreadMessageMailNotification;
use App\Services\Actions\NewNotificationAction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UnreadMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unread:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Once an hour the service checks if users have messages that have been unread for over 12 hours';

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
//        $chats=  Chat::query()
//            ->with(['messages' => fn($q) => $q->unread()])
//            ->whereHas('messages', function ($q){
//                $q->unread()
//                ->where('created_at', '>=', Carbon::now()->subHours(12)->toDateTimeString());
//            })
//
//            ->take(4)->get();
//        $senderArray = collect([]);
//
//        foreach ($chats as $chat) {
//
//            foreach ($chat->messages as $message) {
//
//                $findSenderId = collect([$message->chat->client_id, $message->chat->service_id])
//                    ->filter(fn ($userId) => $userId != $message->user_id)
//                    ->first();
//
//                $senderArray->push(['sender' =>$findSenderId, 'message' => $message->id]);
//            }
//        }


        $messages = Message::query()
            ->unread()
            ->notYetSentToMail()
            ->where('created_at', '>=', Carbon::now()->subHours(12)->toDateTimeString())
            ->get();


        $senders = collect([]);

        foreach ($messages as $message) {

            $findSenderId = collect([$message->chat->client_id, $message->chat->service_id])
                ->filter(fn ($userId) => $userId != $message->user_id)
                ->first();

            $senders->push(['sender' =>$findSenderId, 'message' => $message->id]);
        }

         $senders->groupBy('sender')->map(function ($key) {

            return $key->pluck('message');

        })->map(function ($messageIds, $senderId) {

            $messages = Message::whereIn('id', $messageIds)->get();

            $user = User::find($senderId);

            if($user) {
                $user->notify((new UnreadMessageMailNotification($messages, $user))->locale($user->locale));
            }

        });


    }
}
