<?php

use App\Enums\InvoiceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->unsignedBigInteger('creator_id')->nullable()->index();

            $table->float('sum')->nullable();
            $table->integer('commission_sum')->nullable();
            $table->enum('type', [
                InvoiceType::SUBSCRIPTION_PAYMENT,
                InvoiceType::DONATION,
                InvoiceType::PPV,
                InvoiceType::REFERRAL_SUBSCRIPTION_PAYMENT,
                InvoiceType::REFERRAL_DONATION,
                InvoiceType::REFERRAL_PPV,
                InvoiceType::COMMISSION,
                InvoiceType::WITHDRAWAL
            ]);
            $table->enum('direction', [InvoiceType::DIRECTION_CREDIT, InvoiceType::DIRECTION_DEBIT]);
            $table->string('purpose_string')->nullable();


            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
