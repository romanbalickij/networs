<?php

namespace App\Console\Commands;

use App\Enums\ChatType;
use App\Http\Resources\Chat\ConversationCollection;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Console\Command;

class RegenerateChasSubscCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:regenerate';

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

        User::query()
            ->chunkById(100, function ($users) {

                $users->each(function ($user) {

                    auth()->setUser($user);

                    $chats = Chat::query()
                        ->contentCreator($user)
                        ->get();



                    $subscribers = $user->subscribers->pluck('user_id');

                    $chats->each(function ($chat) use ($subscribers, $user) {

                        if($chat->mode == ChatType::ADMIN) {
                            return;
                        }

                        $mySubsc = $chat->contrepartyUser();

                        if(!$subscribers->contains($mySubsc->id)){
                            $chat->delete();
                            dump($chat->id, "delete={$mySubsc->id}");
                        }




                        if($user->id == 20) {
                        //    dd(20);
                        }


                    });

                });



            });







    }
}
