<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Services\Reports\Invoices\InvoiceReport;
use App\Services\Reports\ReportRepository;
use App\Services\Reports\SavePfd;
use Illuminate\Support\Facades\File;

class UserInvoiceController extends BaseController
{

    public function download(User $user) {

        $zip_file = 'invoices.zip';

        $repository = new ReportRepository(
            new InvoiceReport($user),
            new SavePfd('exports.invoice')
        );

        $invoices = $repository->save();

        try{
            File::archivedZip($zip_file, $invoices);

            return response()->download($zip_file)->deleteFileAfterSend();

        }catch (\Exception $e) {

            return $this->respondError($e->getMessage(),500);
        }

    }
}
