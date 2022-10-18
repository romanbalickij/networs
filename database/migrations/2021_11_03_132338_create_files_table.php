<?php

use App\Enums\FileType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->enum('entity_type', [FileType::MODEL_POST, FileType::MODEL_MESSAGE])->index();
            $table->enum('type', [FileType::TYPE_IMAGE, FileType::TYPE_VIDEO, FileType::TYPE_OTHER]);
            $table->integer('entity_id')->index();
            $table->string('mime_type')->nullable();
            $table->string('url')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
