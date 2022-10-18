<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Donation;
use App\Models\History;
use App\Models\Message;
use App\Models\Post;
use App\Models\PostClickthroughHistory;
use App\Models\PostInterestHistory;
use App\Models\PostShowHistory;
use App\Models\ReferralLink;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HistorySeeder extends Seeder
{

    public function run()
    {
        $actualUserTest = 1;

        DB::table('histories') -> truncate();

        $posts = Post::where('user_id', $actualUserTest)->pluck('id');


        Subscription::where('creator_id' , $actualUserTest)->get()->each(function ($sub) {

            echo "==========> Generating Subscription   .... \n\n";

                $this->generateTestHistory($sub);
        });

        Donation::where('user_id' , $actualUserTest)->get()->each(function ($sub) {

            echo "==========> Generating  Donation   .... \n\n";

            $this->generateTestHistory($sub);
        });

        PostShowHistory::whereIn('post_id' , [...$posts])->get()->each(function ($sub) {

            echo "==========> Generating PostShowHistory  .... \n\n";

            $this->generateTestHistory($sub);
        });

        PostClickthroughHistory::whereIn('post_id' , [...$posts])->get()->each(function ($sub) {

            echo "==========> Generating PostClickthroughHistory .... \n\n";

            $this->generateTestHistory($sub);
        });

        PostInterestHistory::whereIn('post_id' , [...$posts])->get()->each(function ($sub) {

            echo "==========> Generating PostInterestHistory  .... \n\n";

            $this->generateTestHistory($sub);
        });

        Post::where('user_id' , 1)->get()->each(function ($sub) {

            echo "==========> Generating Post  .... \n\n";

            $this->generateTestHistory($sub);
        });

        Message::where('user_id' , $actualUserTest)->get()->each(function ($sub) {

            echo "==========> Generating Messages  .... \n\n";

            $this->generateTestHistory($sub);
        });

        Comment::whereIn('post_id' , [...$posts])->get()->each(function ($sub) {

            echo "==========> Generating Comment .... \n\n";

            $this->generateTestHistory($sub);
        });

        ReferralLink::where('user_id' , $actualUserTest)->get()->each(function ($sub) {

            echo "==========> Generating ReferralLink  .... \n\n";

            $this->generateTestHistory($sub);
        });

    }

    protected function generateTestHistory($model) {

        History::factory(1)
            ->create([
                'user_id'          => User::find(1),
                'historyable_type' => Str::lower(Str::afterLast(get_class($model), '\\')),
                'historyable_id'   => $model->id,
            ]);
    }
}
