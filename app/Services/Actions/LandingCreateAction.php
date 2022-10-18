<?php


namespace App\Services\Actions;


use App\Models\LandingCreator;

class LandingCreateAction
{

    public function execute($payload, $images) :void {

        collect($images)->each(function ($file) use ($payload) {

            $landing = LandingCreator::create($payload);

             env('APP_ENV') === 'production'
                 ? $landing->update(['img' => $file])
                 : $landing->addImage($file);
        });

    }
}
