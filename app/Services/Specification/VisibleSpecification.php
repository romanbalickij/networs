<?php


namespace App\Services\Specification;


use App\Models\File;
use App\Models\User;

class VisibleSpecification implements Specification
{

    private array $specifications;


    public function __construct()
    {
        $this->specifications[] = new HasPaymentSpecification();
        $this->specifications[] = new AllowedAuthorSpecification();
    }

    public function isSatisfiedBy(File $file): bool
    {

        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfiedBy($file)) {
              //  return false;

                return true;
            }
        }

        return false;
    }
}
