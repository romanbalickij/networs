<?php

use App\Enums\PageType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key')->nullable();
            $table->string('name');
            $table->text('title');
            $table->text('meta_description')->nullable();
            $table->string('robots')->nullable();
            $table->string('slug')->nullable();
            $table->text('meta_tags')->nullable();
            $table->enum('type', [PageType::CUSTOM, PageType::DEFAULT])->default(PageType::DEFAULT);
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
        Schema::dropIfExists('pages');
    }
}
