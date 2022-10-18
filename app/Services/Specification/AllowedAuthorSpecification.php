<?php


namespace App\Services\Specification;


use App\Models\File;
use Illuminate\Support\Facades\Auth;

class AllowedAuthorSpecification implements Specification
{

    public function isSatisfiedBy(File $file): bool
    {
        if(!Auth::check()) {
            return false;
        }

        return $file->entity->isAuthoredBy(Auth::id());
    }
}
