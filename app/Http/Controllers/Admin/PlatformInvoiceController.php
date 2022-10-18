<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\Invoice\PlatformInvoiceCollection;
use App\Models\Invoice;
use App\Services\Filters\InvoiceFilter;
use App\Services\Reports\Invoices\PlatformInvoiceReport;
use App\Services\Reports\ReportRepository;
use App\Services\Reports\SavePfd;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class PlatformInvoiceController extends BaseController
{

    public function index(InvoiceFilter $invoiceFilter) {

        $invoices = Invoice::query()
            ->platform()
            ->filter($invoiceFilter)
            ->cursorPaginate($this->perPage());

        return $this->respondWithSuccess(

            new PlatformInvoiceCollection($invoices)
        );
    }

    public function download(Invoice $invoice) {

        $pdf = Pdf::loadView('exports.invoice',['data' => $invoice]);

        return $pdf->download('invoice.pdf');
    }

    public function downloadAll() {

        $zip_file = 'invoices.zip';

        $repository = new ReportRepository(
            new PlatformInvoiceReport(),
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
