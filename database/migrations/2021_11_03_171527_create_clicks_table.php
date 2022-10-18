<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clicks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('ad_campaign_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->string('user_agent')->nullable();
            $table->string('user_ip')->nullable();

            $table->timestamps();

            $table->foreign('ad_campaign_id')
                ->references('id')
                ->on('ad_campaigns')
                ->onDelete('cascade');


            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clicks');
    }
}
