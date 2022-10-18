<?php

namespace App\Http\Resources;

use App\Enums\HistoryType;
use App\Enums\NotificationType;
use App\Http\Resources\Chat\MessageResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Donation\DonationResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Subscription\SubscriptionCollection;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Models\Comment;
use App\Models\Donation;
use App\Models\Post;
use App\Models\PostClickthroughHistory;
use App\Models\PostInterestHistory;
use App\Models\PostShowHistory;
use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TestResource extends JsonResource
{

    public function toArray($request)
    {
        // todo 1
          return  [
                $this->mergeWhen($this->entity_type == 'subscription', new SubscriptionResource($this) )

           ];




    }

    public function pagination() {

        return [
      //      'per_page'   => $this->perPage(),
         //   'next_page'  => $this->nextPageUrl()
        ];
    }

    public function toResponse($request)
    {
        return JsonResource::toResponse($request);
    }
}
