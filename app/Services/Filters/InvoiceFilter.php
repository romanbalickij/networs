<?php


namespace App\Services\Filters;


class InvoiceFilter extends QueryFilter
{

    public function type($value) {

        $this->builder->where('type', '=', $value);
    }

    public function date($value) {

        $this->builder->whereDate('created_at', $value);
    }

    public function purpose($value) {

        $this->builder->where('purpose_string','LIKE', "%$value%");
    }

    public function name($value) {

        $this->builder->whereHas('owner',
            fn($query) => $query->where(
                fn($query) => $query
                    ->where('name', 'LIKE', "%$value%")
                    ->orWhere('surname', 'LIKE', "%$value%")
            )
        );
    }


}
