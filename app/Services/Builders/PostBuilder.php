<?php

namespace App\Services\Builders;

use App\Enums\PostType;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostBuilder extends Builder
{

    public function authorNotBanned() {

        $this->whereHas('user',
            fn ($query) => $query->where(fn ($q) => $q->notBlocked()->verified())
        );
        return $this;
    }

    public function orderByBestCharacteristics() {

        $this->orderBy(DB::raw("`shows` + `interested` + `clickthroughs`"), 'desc');

        return $this;
    }

    public function filter($filters) {

        return $filters->apply($this);
    }

    public function pinnedDisplayedAboveAll() {

      return $this->orderBy('is_pinned', 'desc');
    }

    public function orderDate($direction = 'DESC') {

        return $this->orderBy('created_at', $direction);
    }

    public function orderComments($direction = 'DESC') {

        return $this->orderBy('all_comments_count', $direction);
    }

    public function olderThanMonth() {

        return $this->where('created_at', '>=', Carbon::now()->subDays(30));
    }

    public function orderReactios($direction = 'DESC') {

        return $this->orderBy('reaction_count', $direction);
    }

    public function access(string $access) {

        return $this->where('access', $access);
    }

    public function visibleUntil() {
        //fix mariaDb
       // $null = env('APP_ENV') == 'production' ? '0000-00-00 00:00:00' : null;
        $null = null;
        return $this->where(function ($query) use($null){
            $query->where('visible_until' , '=', $null)->Orwhere('visible_until', '>=', now());
        });
    }

    public function allowedPrivatePostForSubscriber($author, $user) {

        if(Auth::check() and $author->isMyProfile($user)) {
            return $this;
        }

        return $this->when(!(Auth::check() and $author->load('subscribers')->isMySubscribed($user)),

            fn ($query) => $query->access(PostType::PUBLIC)
        );
    }

    public function algorithmSortingPosts(?string $typeSort = PostType::SORT_HOT) {

        return $this
            ->when($typeSort == PostType::SORT_NEW, fn ($query)      => $query->orderDate())
            ->when($typeSort == PostType::SORT_TOP_MONTH, fn($query) => $query->olderThanMonth()->orderReactios())
            ->when($typeSort == PostType::SORT_TOP, fn ($query)      => $query->orderReactios())
            ->when($typeSort == PostType::SORT_MOST_DISCUSSED_MONTH, fn ($query) => $query->withCount(['allComments' => fn($q) => $q->olderThanMonth()])->orderComments())
            ->when($typeSort == PostType::SORT_MOST_DISCUSSED, fn ($query) => $query->withCount(['allComments'])->orderComments())

            ->when($typeSort == PostType::SORT_HOT, function ($query) {

                ['rand_coefficient' => $B] = getJsonConfig('algorithm-sort-config');

                $maxValue = User::max('quality') ?? 0;

                $query
                    ->orderBy('quality', 'DESC')
                    ->orderByRaw("RAND(id)*$B + (posts.quality_for_creator / $maxValue)");
            })
            ->when($typeSort == PostType::SUBSCRIPTIONS and Auth::check(), function ($query) {

                $authorIds = Subscription::where('user_id', Auth::id())->pluck('creator_id')->toArray();

                $query->orderDate()->whereIn('user_id', array_merge($authorIds, [Auth::id()]));

            });
    }
}
