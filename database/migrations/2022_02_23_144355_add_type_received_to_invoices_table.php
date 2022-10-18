<?php

use App\Enums\InvoiceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeReceivedToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('type_received', [
                InvoiceType::RECEIVED_PAYMENT,
                InvoiceType::MAKE_PAYMENT,
                InvoiceType::RECEIVE_PLATFORM,
                InvoiceType::RECEIVED_REFERRAL,
                InvoiceType::MAKE_WITHDRAWAL,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('type_received');
        });
    }
}
