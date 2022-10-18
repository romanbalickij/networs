<?php

use App\Enums\MessageType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->unsignedBigInteger('chat_id')->index();

            $table->text('text')->nullable();
            $table->boolean('read');
            $table->boolean('is_ppv')->default(false);
            $table->integer('ppv_price')->nullable();
            $table->enum('meta', [
                MessageType::ADMIN_ENTERED,
                MessageType::ADMIN_LEFT,
                MessageType::USER_DONATED,
                MessageType::DEFAULT
            ])->default(MessageType::DEFAULT)->nullable();

            $table->timestamps();

            $table->foreign('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade');

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
        Schema::dropIfExists('messages');
    }
}
