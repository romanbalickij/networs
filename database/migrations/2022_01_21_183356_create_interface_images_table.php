<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterfaceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interface_images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('interface_text_id');

            $table->string('name')->nullable();
            $table->string('url')->nullable();

            $table->timestamps();

            $table->foreign('interface_text_id')
                ->references('id')
                ->on('interface_texts')
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
        Schema::dropIfExists('interface_images');
    }
}
