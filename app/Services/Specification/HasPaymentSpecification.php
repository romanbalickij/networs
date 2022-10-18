<?php


namespace App\Services\Specification;


use App\Models\File;
use Illuminate\Support\Facades\Auth;

class HasPaymentSpecification implements Specification
{

    public function isSatisfiedBy(File $file): bool
    {
        // if disabled ppv return ok
        if(!$file->isEntityPPV()) {
            return true;
        }

        return $file->isUnlockedFor(Auth::id());
    }
}
