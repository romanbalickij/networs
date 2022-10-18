<?php


namespace App\Services\Filters;


class PostFilter extends QueryFilter
{

    public function search($value) {

        $this->builder->where(function ($q) use ($value) {

            $q->where('text', 'LIKE', "%$value%")
            ->orwhereHas('owner', fn ($q) => $q->where('name', 'like', "%$value%")->Orwhere('surname', 'like', "%$value%"));
        });
    }
}
