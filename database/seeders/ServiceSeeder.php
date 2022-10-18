<?php

namespace Database\Seeders;

use App\Enums\BookmarkType;
use App\Enums\ChatType;
use App\Enums\FileType;
use App\Enums\NotificationType;
use App\Enums\ReactionType;
use App\Enums\UserType;
use App\Models\AccountManager;
use App\Models\AdCampaign;
use App\Models\BlockedUser;
use App\Models\Bookmark;
use App\Models\Chat;
use App\Models\Click;
use App\Models\Comment;
use App\Models\Donation;
use App\Models\File;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Post;
use App\Models\PostClickthroughHistory;
use App\Models\PostInterestHistory;
use App\Models\PostShowHistory;
use App\Models\Reaction;
use App\Models\SubscriberGroup;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;


class ServiceSeeder extends Seeder
{

    const USER_SEEDER = 450; // 1500

    public function run()
    {
        $this->generateFakeData();
    }

    protected function generateFakeData() {
        $i = 0;

        echo "==========> Generating Services  $i \n\n";
        echo "==========> Wait  \n\n";
        $this->generateService($i);

        echo "==========>Generating Super User  \n\n";
        $this->generateSuperUser();
        echo "==========> Done";

        echo "==========>Generating Room Admin  \n\n";
        $this->generateAdminRoom();
        echo "==========> Done";

        echo "==========>Generating Post data  \n\n";
        $this->generatePostFakeData();

        echo "==========>Generating  data for Post  \n\n";
        $this->generateFakeDataForPost();

        echo "==========>Generating Statistics For Post  \n\n";
        $this->generateStatisticsForPost();

        DB::unprepared(file_get_contents("database/sql/date.sql"));

        $i++;
    }

    protected function generateService($i) {

        User::factory(self::USER_SEEDER)
            ->hasReferralLinks(3)
            ->has(
                Plan::factory()
                    ->count(2)
                    ->state(new Sequence(
                        ['name' => 'Basic'],
                        ['name' => 'Standard'],
                    ))
            )
            ->hasSubscriberGroups(1, [
                'name' => 'Friends',
            ])
            ->hasSubscriberGroups(1, [
                'name' => 'Family',
            ])
            ->create()
            ->each(function ($user) use(&$i) {

                echo "==========> Generating Data for User - $i Invoices  .... \n\n";
                $this->generateFakeInvoice($user);

                echo "==========> Generating Data for User - $i Blocking.... \n\n";
                $this->generateFakeBlocking($user);

                echo "==========> Generating Data for User - $i Donations  .... \n\n";
               $this->generateFakeDonation($user);

                echo "==========> Generating Data for User - $i Subscriptions  .... \n\n";
                $this->generateFakeSubscription($user);

                echo "==========> Generating Data for User - $i AdCampaign  .... \n\n";
                $this->generateFakeAdCampaign($user);

                echo "==========> Generating Data for User - $i AccountManager  .... \n\n";
                $this->generateFakeAccountManager($user);
            $i++;
        });
    }

    protected function generateFakeAccountManager($owner) {

         AccountManager::factory()
             ->count(2)
             ->state(new Sequence(
                 fn ($sequence) => [ 'user_id' => User::whereNotIn('id', [$owner->id])->inRandomOrder()->first()->id],
             ))
             ->create([
                 'manages_user_id' => $owner,
             ]);

    }

    protected function generateFakeAdCampaign($user) {

        Schema::disableForeignKeyConstraints();

        AdCampaign::factory()
            ->for($user)
            ->count(3)
            ->create()->each(function ($campaign) use($user) {
                Click::factory()
                    ->count(5)
                    ->state(new Sequence(
                        ['user_id' => User::whereNotIn('id', [$user->id])->inRandomOrder()->first()->id],
                        ['user_id' => rand($user->id + 1, self::USER_SEEDER)],
                        ['user_id' => NULL],
                        ['user_id' => NULL],
                        ['user_id' => NULL],
                    ))
                    ->create(['ad_campaign_id' => $campaign->id]);
            });

        Schema::enableForeignKeyConstraints();
    }

    protected function generateFakeSubscription($creator) {

        Subscription::factory(50)
            ->for(Plan::factory()->state([
                'user_id' => $creator->id,
                'name'    => 'Enterprise'
            ]))
            ->state(new Sequence(
                ['subscriber_group_id' => SubscriberGroup::where('name', 'Friends')->first()->id],
                ['subscriber_group_id' => SubscriberGroup::where('name', 'Family')->first()->id],
                ['subscriber_group_id' => NULL],
            ))
            ->hasInformings(1, fn(array $attributes, Subscription $subscription) => ['entity_type' => NotificationType::SUBSCRIPTION, 'user_id' => $creator->id])
            ->create(['creator_id' => $creator->id])
            ->each(function ($subs) use ($creator){


                $user = User::whereNotIn('id', [$subs->creator_id])->inRandomOrder()->first();

                $subscription = Subscription::where('user_id', $user->id)->where('creator_id', $subs->creator_id)->first();


                !isset($subscription->user_id) ? $subs->update(['user_id' => $user->id]) : $subs->delete();

                $this->generateFakeChat($creator, $user);
            });
    }

    protected function generateFakeChat($user, $subs) {

      $chats = Chat::factory()
            ->count(1)
            ->make(['client_id'=> $user->id, 'service_id' => $subs->id]);


        $chunks = $chats->chunk(10);

        $chunks->each(function ($chunk) {

            Chat::insert($chunk->toArray());

            echo "==========> Generating Chat  Insert\n\n";
        });

           $count = Chat::count();

            echo 'Generating Chat ...' .$count;


        $this->generateFakeMessage($user->id, $count);
        $this->generateFakeMessage($subs->id, $count);
    }

    protected function generateFakeMessage($id, $chat) {

      $messages = Message::factory()
            ->count(2)
            ->make(['user_id' => $id, 'chat_id' => $chat]);


        $chunks = $messages->chunk(10);


        $chunks->each(function ($chunk) {

            Message::insert($chunk->toArray());

            echo "==========> Generating Message  Insert\n\n";
        });

        $count = Message::count();

        $this->generateFakeFile('other/file.txt', FileType::MODEL_MESSAGE, $count , FileType::TYPE_OTHER, 'file.txt', 'text/txt', $id);

       $reactions =  Reaction::factory()->count(5)
            ->make(['entity_type' => ReactionType::MODEL_MESSAGE, 'entity_id' => $count]);

        $reactionsChunks = $reactions->chunk(10);
        $reactionsChunks->each(function ($chunk) {

            Reaction::insert($chunk->toArray());

            echo "==========> Generating Reaction  Insert\n\n";
        });


          Notification::factory()->count(1)->create(['entity_type' => NotificationType::UNREAD_MESSAGES, 'entity_id' => $count, 'user_id' => $id]);



        echo "==========> Generating Message  $count\n\n";

    }

    protected function generateFakeDonation($donated) {

        Donation::factory()->count(5)
            ->hasInformings(1, fn(array $attributes, Donation $donation) => ['entity_type' => NotificationType::DONATION, 'user_id' => $donation->user_id])
            ->create([
                'user_id'     => $donated->id,
                'creator_id'  => User::whereNotIn('id', [$donated->id])->inRandomOrder()->first()->id]);
    }

    protected function generateFakeBlocking($author) {

        $countUser = $this->countUser();

        BlockedUser::factory()
            ->count(1)
            ->create([
                'bloquee_id' => $author->id,
                'user_id'    => rand(1, $countUser)]);
    }

    protected function generateFakeInvoice($user) {

        Invoice::factory()->count(5)
            ->create([
                'creator_id' => $user->id,
                'user_id'    => User::whereNotIn('id', [$user->id])->inRandomOrder()->first()->id]);
    }

    protected function generateStatisticsForPost() {

         $this->postReactions();

         $this->postShowHistories();

         $this->postInterestHistories();

         $this->postClickthroughHistories();
    }

    protected function generateFakeDataForPost() {

        Post::take(15000)->get()->each(function ($post) {

            $this->postBookmarks($post);

            $this->generateFakeComment($post);
//
            $this->generateFakeFile('/images/avatar.png', FileType::MODEL_POST, $post->id, FileType::TYPE_IMAGE, 'avatar.png', 'image/png', $post->user_id);
            $this->generateFakeFile('/video/video.mp4', FileType::MODEL_POST, $post->id, FileType::TYPE_VIDEO, 'video.mp4', 'video/mp4', $post->user_id);

        });
    }

    protected function postReactions() {

        $showHistories = Reaction::factory()->count(200000)
            ->make();

        $chunks = $showHistories->chunk(10);
//
        for($i = 0; $i< 20000; $i++){

            $usersTemp = $chunks->get($i);

            $usersTemp->each(function ($user) use ($i) {
                $post = Post::find($i+1);
                echo "==========> Generating Reaction for Post  $post->id\n\n";

                $user->entity_type = ReactionType::MODEL_POST;
                $user->entity_id = $post->id;
            });
        }

        $j = 0;
        $chunks->each(function ($chunk) use(&$j) {

            Reaction::insert($chunk->toArray());

            echo "==========> Generating Reaction for Post  $j\n\n";

            $j++;
        });
    }

    protected function postBookmarks($post) {

        $bookmarks = Bookmark::factory(1)->make(['entity_type' => BookmarkType::MODEL_POST, 'entity_id' => $post->id,'user_id' => User::all()->random()->id]);

        $chunks = $bookmarks->chunk(1);

        $chunks->each(function ($chunk) use($post ) {

            Bookmark::insert($chunk->toArray());

            echo "==========> Generating Bookmark for Post  $post->id\n\n";
        });
    }

    protected function postShowHistories() {

        $showHistories = PostShowHistory::factory()->count(200000)
            ->make();

        $chunks = $showHistories->chunk(10);
//
        for($i = 0; $i< 20000; $i++){

               $usersTemp = $chunks->get($i);

                   $usersTemp->each(function ($user) use ($i) {
                       $post = Post::find($i+1);
                       echo "==========> Generating ShowHistories for Post  $post->id\n\n";

                       $user->user_id = $post->user_id;
                       $user->post_id = $post->id;
                   });
        }

        $j = 0;
        $chunks->each(function ($chunk) use(&$j) {

            PostShowHistory::insert($chunk->toArray());

            echo "==========> Generating ShowHistories for Post  $j\n\n";

            $j++;
        });
    }

    protected function postInterestHistories() {

        $showHistories = PostInterestHistory::factory()->count(200000)
            ->make();

        $chunks = $showHistories->chunk(10);
//
        for($i = 0; $i< 20000; $i++){

            $usersTemp = $chunks->get($i);

            $usersTemp->each(function ($user) use ($i) {
                $post = Post::find($i+1);
                echo "==========> Generating PostInterestHistory for Post  $post->id\n\n";

                $user->user_id = $post->user_id;
                $user->post_id = $post->id;
            });
        }

        $j = 0;
        $chunks->each(function ($chunk) use(&$j) {

            PostInterestHistory::insert($chunk->toArray());

            echo "==========> Generating PostInterestHistory for Post  $j\n\n";

            $j++;
        });
    }

    protected function postClickthroughHistories() {

        $showHistories = PostClickthroughHistory::factory()->count(200000)
            ->make();

        $chunks = $showHistories->chunk(10);
//
        for($i = 0; $i< 20000; $i++){

            $usersTemp = $chunks->get($i);

            $usersTemp->each(function ($user) use ($i) {
                $post = Post::find($i+1);
                echo "==========> Generating PostClickthroughHistory for Post  $post->id\n\n";

                $user->user_id = $post->user_id;
                $user->post_id = $post->id;
            });
        }

        $j = 0;
        $chunks->each(function ($chunk) use(&$j) {

            PostClickthroughHistory::insert($chunk->toArray());

            echo "==========> Generating PostClickthroughHistory for Post  $j\n\n";

            $j++;
        });
    }

    protected function generatePostFakeData() {
        $j = 0;

        User::all()->each(function ($user)  use (&$j) {

          $posts = Post::factory()
                ->count(70)
                ->make(['user_id' => $user->id]);

          $chunks = $posts->chunk(40);

          $chunks->each(function ($chunk) use($user, &$j) {

              Post::insert($chunk->toArray());

              echo "==========> Generating Data for Post $j  user $user->id\n\n";

              $j++;

            });

        });
    }

    protected function generateFakeFile(string $url, string $entityType, int $entityId, string $type, string $fileName, string $mineType, $user) {

        echo "==========> Generating File for $entityType  $entityId\n\n";

        File::factory(2)
            ->hasBookmarks(1, fn(array $attributes, File $file) => ['entity_type' => BookmarkType::MODEL_ATTACHMENT, 'user_id' => $user])
            ->create([
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'mime_type'   => $mineType,
                'type'        => $type,
                'url'         => $url,
                'name'        => $fileName
            ]);
    }

    protected function generateFakeComment($post) {

        echo "==========> Generating Comment for Post $post->id\n\n";

        Comment::factory(3)
            ->hasInformings(1, fn(array $attributes, Comment $comment) => ['entity_type' => NotificationType::COMMENT, 'user_id' => $comment->user_id])
            ->create(['post_id' => $post->id, 'user_id' => User::all()->random()->id])
            ->each(function ($comment) use($post) {

                Comment::factory()
                    ->hasInformings(1, fn(array $attributes, Comment $comment) => ['entity_type' => NotificationType::COMMENT, 'user_id' => $comment->user_id])
                    ->hasReplies(1, ['post_id' => $post->id,    'user_id' => User::all()->random()->id])
                    ->create(['responds_to_id' => $comment->id, 'post_id' => $post->id, 'user_id' => $post->user_id]);
            });
    }

    protected function countUser() {

      return User::count();
    }

    protected function generateFakeSetting($user) {

        $user->setSettings([
            'theme'                     => 'dark',
            'page_post_visibility'      => 'public',

            'display_online_status'     => true,
            'display_subscriber_number' => true,
            'auto_prolong_subscription' => true,


            //email preferences:
            'reaction'          => true,
            'subscription'      => true,
            'donation'          => true,
            'unread_message'    => true,
            'comment_response'  => true,
            'invoice'           => true,
            'promotion'         => true,
        ]);
    }

    protected function generateSuperUser() {

        $user = User::find(1);
        $user -> email    = 'user@user.com';
        $user -> password =  Hash::Make('user@user.com');
        $user->role = UserType::USER;
        $user->verified = true;
        $user->blocked = false;
        $user -> save();
        $this->generateFakeSetting($user);

        $admin = User::find(2);
        $admin -> email    = 'admin@admin.com';
        $admin -> password =  Hash::Make('admin@admin.com');
        $admin->role = UserType::ADMIN;
        $user->verified = true;
        $user->blocked = false;
        $admin -> save();
        $this->generateFakeSetting($admin);

        Chat::first()->delete();
    }

    protected function generateAdminRoom() {

         User::query()
            ->where('role', '!=', ChatType::ADMIN)
            ->chunkById(50, function($users) {

                foreach ($users as $user) {

                    $admin = User::where('role', 'admin')->first();

                    Chat::factory()
                        ->count(1)
                        ->state(new Sequence(
                            ['service_id' => array_rand([null, $admin->id]) == 0 ? null : $admin->id],
                        ))
                        ->create(['client_id'=> $user->id, 'mode' => ChatType::ADMIN]);
                }
            });
    }
}
