<?php


namespace App\Services\Specification;


use App\Models\File;

interface Specification
{
    public function isSatisfiedBy(File $file): bool;
}
