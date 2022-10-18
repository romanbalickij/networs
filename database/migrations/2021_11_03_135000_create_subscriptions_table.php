<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('is_paid')->default(false);

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('plan_id')->index();
            $table->unsignedBigInteger('creator_id')->index();


            $table->integer('subscriber_group_id')->nullable()->index();

            $table->timestamp('last_payment_date');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');


            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
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
        Schema::dropIfExists('subscriptions');
    }
}
