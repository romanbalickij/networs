<?php

namespace Database\Seeders;

use App\Enums\InteractionType;
use App\Models\Comment;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InteractionSeeder extends Seeder
{

    public function run()
    {
        DB::table('interactions') -> truncate();

        $user = User::find(1);
        $limit = 50;

        // Chat creations
        $user->chats()
            ->limit($limit)
            ->get()
            ->each(fn ($chat) => $chat->pushToInteractions(InteractionType::TYPE_CHAT_CREATION, $user));

        // Bookmarking and reaction post
        $user->posts()
            ->limit($limit)
            ->get()
            ->each(fn ($post) => $post->pushToInteractions(collect([InteractionType::TYPE_BOOKMARKING, InteractionType::TYPE_REACTION])->random(), $user));

        // Bookmarking and reaction messages
        $user->messages()
            ->limit($limit)
            ->get()
            ->each(fn ($messages) => $messages->pushToInteractions(collect([InteractionType::TYPE_BOOKMARKING, InteractionType::TYPE_REACTION])->random(), $user));

        // Bookmarking files
        File::limit($limit)
            ->get()
            ->each(fn ($messages) => $messages->pushToInteractions(InteractionType::TYPE_BOOKMARKING, $user));

        // Bookmarking users
        User::limit($limit)
            ->get()
            ->each(fn ($messages) => $messages->pushToInteractions(InteractionType::TYPE_BOOKMARKING, $user));

        // Commenting and  Receiving responses for comments
        Comment::where('user_id', $user->id)->whereNull('responds_to_id')->limit($limit)->get()->each(function ($comment) use($user) {

            $comment->pushToInteractions(InteractionType::TYPE_COMMENTING, $user);

            $comment->replies->each(function ($replies) use ($user) {

                $replies->pushToInteractions(InteractionType::TYPE_RESPONSE_COMMENT, $user);
            });
        });

        //Subscriptions, Subscription cancellations, New subscribers, Subscriber cancellations
        $user->subscriptions()->limit($limit + 20)->get()->each(function ($subscription) use ($user) {

            $type = collect([
                InteractionType::TYPE_SUBSCRIPTION_CANCELLATION,
                InteractionType::TYPE_SUBSCRIBER_CANCELLATION,
                InteractionType::TYPE_SUBSCRIPTION,
                InteractionType::TYPE_NEW_SUBSCRIBER
            ])->random();

            $subscription->pushToInteractions($type, $user);
        });

        //Invoice creations / billings
        $user->creatorInvoices()
            ->limit($limit)
            ->get()
            ->each(fn ($invoice) =>  $invoice->pushToInteractions(collect([InteractionType::TYPE_INVOICE_CREATION, InteractionType::TYPE_INVOICE_BILLING])->random(), $user));

        //Sent donations  Received donations
        $user->donations()
            ->limit($limit)
            ->get()
            ->each(fn ($donation) =>  $donation->pushToInteractions(collect([InteractionType::TYPE_SEND_DONATION, InteractionType::TYPE_RECEIVED_DONATION])->random(), $user));

        // Account verification , Account blocking / unblocking
        $managers = User::whereNotIn('id', [$user->id])->inRandomOrder()->take(3)->get();

        $managers->each(function ($manager, $index) use($user) {

            $user->ownerAccountManagers()->create([
                'user_id' => $manager->id
            ]);

            $index += 1;

            switch ($index) {
                case 1:
                    $manager->update(['verified' => true]);
                    $manager->pushToInteractions(InteractionType::TYPE_ACCOUNT_VERIFICATION, $user);
                    break;
                case 2:
                    $manager->update(['blocked' => true]);
                    $manager->pushToInteractions(InteractionType::TYPE_ACCOUNT_BLOCKING, $user);
                    break;
                case 3:
                    $manager->update(['blocked' => false]);
                    $manager->pushToInteractions(InteractionType::TYPE_ACCOUNT_UNBLOCKING, $user);
                    break;
            }
        });
    }

}
