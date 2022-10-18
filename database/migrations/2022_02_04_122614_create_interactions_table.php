<?php

use App\Enums\InteractionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interactions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->morphs('interactionable');
            $table->unsignedBigInteger('user_id')->index();

            $table->enum('type', [
                InteractionType::TYPE_CHAT_CREATION,
                InteractionType::TYPE_BOOKMARKING,
                InteractionType::TYPE_COMMENTING,
                InteractionType::TYPE_REACTION,
                InteractionType::TYPE_RESPONSE_COMMENT,
                InteractionType::TYPE_SUBSCRIPTION_CANCELLATION,
                InteractionType::TYPE_SEND_DONATION,
                InteractionType::TYPE_RECEIVED_DONATION,
                InteractionType::TYPE_SUBSCRIBER_CANCELLATION,
                InteractionType::TYPE_SUBSCRIPTION,
                InteractionType::TYPE_INVOICE_CREATION,
                InteractionType::TYPE_INVOICE_BILLING,
                InteractionType::TYPE_ACCOUNT_VERIFICATION,
                InteractionType::TYPE_ACCOUNT_BLOCKING,
                InteractionType::TYPE_ACCOUNT_UNBLOCKING,
                InteractionType::TYPE_NEW_SUBSCRIBER
            ]);
            $table->string('action')->nullable();
            $table->timestamps();


            $table->foreign('user_id')
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
        Schema::dropIfExists('interactions');
    }
}
