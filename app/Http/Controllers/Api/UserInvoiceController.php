<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Resources\User\UserInvoiceCollection;
use App\Models\User;
use App\Services\Filters\InvoiceFilter;


class UserInvoiceController extends BaseController
{

    public function __invoke(User $user, InvoiceFilter $invoiceFilter)
    {

        $user->load(['invoices' =>

            fn($query) => $query->invitedInvoices()->filter($invoiceFilter)
        ]);

        return $this->respondWithSuccess(

            new UserInvoiceCollection($user->invoices)
        );
    }
}
