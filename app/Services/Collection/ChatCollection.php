<?php


namespace App\Services\Collection;

use App\Models\SubscriberGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatCollection extends Collection
{

    public function useCollectionfilter($payload) {

        return $this
            ->when(Arr::has($payload, 'group'), function ($collection) use($payload) {

            $group = Arr::get($payload, 'group');

            $value = SubscriberGroup::where('creator_id', Auth::id())->where('name', $group)->first();

            if(!$value) {return $collection;}


            return $collection->filter(function ($item) use($value)  {

                return $item->contrepartyUser()->subscriptions->contains(

                    fn($subscription) => ($subscription->subscriber_group_id == $value->id and $subscription->creator_id == Auth::id())
                );
            });
        })
            ->when(Arr::has($payload, 'user'), function ($collection) use($payload) {

                return $collection->filter(

                    fn ($item) => Str::contains(optional($item->contrepartyUser())->fullName, Arr::get($payload, 'user'))
                );
            });
    }
}
