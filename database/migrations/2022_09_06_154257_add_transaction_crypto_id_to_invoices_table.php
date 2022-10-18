<?php

use App\Enums\InvoiceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionCryptoIdToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('transaction_crypto_id')->nullable();
            $table->enum('status', [
                InvoiceType::STATUS_FAILED,
                InvoiceType::STATUS_SUCCESS,
                InvoiceType::STATUS_PENDING,
                InvoiceType::STATUS_DEFAULT,
            ])->nullable();
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
            $table->dropColumn('transaction_crypto_id');
            $table->dropColumn('status');
        });
    }
}
