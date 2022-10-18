<?php

use App\Enums\InvoiceType;
use App\Enums\WithdrawType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCryptoTypeToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('crypto_type',[
                WithdrawType::CRYPTO_TYPE_TRON,
                WithdrawType::CRYPTO_TYPE_ETHEREUM
            ])->nullable()->default(null);
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
            $table->dropColumn('crypto_type');
        });
    }
}
