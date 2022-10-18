<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;

use App\Services\Actions\Statistics\GraphGenerateAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends BaseController
{
    public function __invoke(GraphGenerateAction $graphGenerateAction) {

        $graphs = $graphGenerateAction->execute();

        return $this->respondWithSuccess($graphs);
    }
}
