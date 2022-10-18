<?php


namespace App\Services\Filters;


class UserFilter extends QueryFilter
{

    public function user($value) {

        $this->builder->where('name', 'LIKE', "%$value%");
    }

    public function email($value) {

        $this->builder->where('email', 'LIKE', "%$value%");
    }

    public function role($value) {

        $this->builder->where('role', $value);
    }

}
