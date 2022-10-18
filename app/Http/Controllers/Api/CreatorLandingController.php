<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\User\LandingCreatorRequest;
use App\Http\Resources\User\LandingCreatorCollection;
use App\Models\LandingCreator;
use App\Models\User;
use App\Services\Actions\LandingCreateAction;
use Illuminate\Http\Request;

class CreatorLandingController extends BaseController
{

    public function index() {

        $user = user()->load('landings');

        return $this->respondWithSuccess(

            (new LandingCreatorCollection($user->landings))->except('creator')
        );
    }

    public function show($username) {

        $user = User::query()
            ->whereCreatorUserName($username)
            ->firstOrFail();

        return $this->respondWithSuccess(

            (new LandingCreatorCollection($user->landings, $user))
        );
    }

    public function store(LandingCreatorRequest $request, LandingCreateAction $createAction) {

        $createAction->execute($request->payload(),  env('APP_ENV') === 'production' ? $request->get('img') : $request->file('img'));

        return $this->respondWithSuccess('ok');
    }

    public function delete(LandingCreator $landingCreator) {

        $landingCreator->deleteLanding();

        return $this->respondWithSuccess('ok');
    }
}
