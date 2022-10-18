<?php

namespace App\Services\Builders;

use App\Enums\InvoiceType;
use App\Enums\PostType;
use App\Enums\UserType;
use App\Models\Donation;
use App\Models\Message;
use App\Models\Post;
use App\Models\ReferralLink;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserBuilder extends Builder
{

    public function notBlocked() {

        $this->where('blocked', false);

        return $this;
    }

    public function verified() {

        $this->where('verified', true);

        return $this;
    }

    public function orderByPopularTrendingPostShow() {


        $this->orderBy('quality', 'desc');
//        $this->join('posts', 'posts.user_id', 'users.id')
//            ->groupBy('users.id')
//            ->select('users.*', 'posts.shows')
//            ->orderBy('posts.shows','desc');

        return $this;
    }

    public function referralTotalEarned() {

        return $this
            ->withSum(['invoices' => fn ($query) => $query->currentCreator()
                ->whereIn('type', [
                    InvoiceType::REFERRAL_PPV,
                    InvoiceType::REFERRAL_SUBSCRIPTION_PAYMENT,
                    InvoiceType::REFERRAL_DONATION
                ])
            ], 'sum');
    }

    public function interactionStatistics() {

        return $this->withCount([
            'histories as total_subscriptions'   => fn($query) => $query->type(Subscription::class),
            'histories as total_donations'       => fn($query) => $query->type(Donation::class),
            'histories as post_earnings'         => fn($query) => $query->type(Post::class)->sumPPV(),
            'histories as message_earnings'      => fn($query) => $query->type(Message::class)->sumPPV(),
            'histories as subscription_earnings' => fn($query) => $query->type(Subscription::class)->sumPPV(),
            'histories as donation_earnings'     => fn($query) => $query->type(Donation::class)->sumPPV(),
            'histories as referrals_earnings'    => fn($query) => $query->type(ReferralLink::class)->sumPPV(),
        ]);
    }

    public function isNotAdmin() {

        return $this->where('role', '!=', UserType::ADMIN);
    }

    public function filter($filters) {

        return $filters->apply($this);
    }

    public function countPost($authorId, $currentUserId) {

        return $this->withCount(['posts' => function($q) use($authorId, $currentUserId) {

            $q->visibleUntil();

            if(Auth::check() and $authorId == $currentUserId) {
                return;
            }

            $q->when(Subscription::where(['creator_id' => $authorId, 'user_id' => $currentUserId])->doesntExist(),

               fn ($query) => $query->access(PostType::PUBLIC)
            );


        } ]);
    }

    public function countMediaPostCount() {

        return $this->withSum(['posts'=> fn($q) => $q->visibleUntil()], 'media_count');
    }

    public function countMediaPostImageCount() {

        return $this->withSum(['posts'=> fn($q) => $q->visibleUntil()], 'image_count');
    }

    public function countMediaPostVideoCount() {

        return $this->withSum(['posts'=> fn($q) => $q->visibleUntil()], 'video_count');
    }

    public function countSubscribers() {

        return $this->withCount('subscribers');
    }

    public function sumReactionPost() {

        return $this->withSum(['posts' => fn($q) => $q->visibleUntil()], 'reaction_count');
    }

    public function whereCreatorUserName($username) {

        return $this->nickname($username);
    }

    public function nickname($nik) {

        return $this->where('nickname', $nik);
    }

    public function online() {

        return $this->where('is_online', true);
    }
}
