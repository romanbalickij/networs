<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SubscriberGroup\SubscriberGroupRequest;
use App\Http\Resources\SubscriberGroup\SubscriberGroupCollection;
use App\Http\Resources\SubscriberGroup\SubscriberGroupIndexCollection;
use App\Http\Resources\SubscriberGroup\SubscriberGroupIndexResource;
use App\Models\SubscriberGroup;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriberGroupController extends BaseController
{

    public function index() {

        $user = user()
            ->load([
                'subscriberGroups' => fn($query) => $query->countSubscriptions(),
                'subscriberGroups.latestSubscriptions',
                'subscriberGroups.latestSubscriptions.user',
                'subscriberGroups.latestSubscriptions.user.plans',
            ]);

        return $this->respondWithSuccess(
            new SubscriberGroupIndexCollection($user->subscriberGroups)
        );
    }

    public function store(SubscriberGroupRequest $request) {

       $group = SubscriberGroup::updateOrCreate($request->payload());

        return $this->respondWithSuccess(new SubscriberGroupIndexResource($group));
    }

    public function update(Request $request, SubscriberGroup $subscriberGroup) {

        $group = tap($subscriberGroup)->update($request->all());

        return $this->respondWithSuccess(new SubscriberGroupIndexResource($group));
    }

    public function joinSubscription(SubscriberGroup $subscriberGroup, Subscription $subscription) {

        $subscription->setGroup($subscriberGroup);

        return $this->respondWithSuccess('Success');
    }

    public function deleteSubscriptionGroup(Subscription $subscription) {

        $subscription->deleteGroup();

        return $this->respondWithSuccess('Success');
    }

    public function delete(SubscriberGroup $subscriberGroup) {

        $subscriberGroup->delete();

        return $this->respondWithSuccess('Success');
    }
}
