<?php


namespace App\Mixins;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginateMacros
{
    public function paginate()
    {
        return function($perPage, $total = null, $page = null, $pageName = 'page') {

            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        };
    }
}
