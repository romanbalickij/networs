<?php

use App\Enums\NotificationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id')->index();

            $table->enum('entity_type', [
                NotificationType::REACTION_POST,
                NotificationType::REACTION_MESSAGE,
                NotificationType::SUBSCRIPTION,
                NotificationType::DONATION,
                NotificationType::UNREAD_MESSAGES,
                NotificationType::COMMENT,
                NotificationType::ENFORCEMENT,

                NotificationType::ACCOUNT_VERIFIED,
                NotificationType::ACCOUNT_BLOCKED,
                NotificationType::ACCOUNT_UNBLOCKED,
                NotificationType::ACCOUNT_COMMENT_MODERATED,
            ])->index();

            $table->integer('entity_id')->index();
            $table->integer('client_id')->index()->nullable();
            $table->boolean('read')->default(false);

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
        Schema::dropIfExists('notifications');
    }
}
