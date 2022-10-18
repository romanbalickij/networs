<?php

use App\Enums\PostType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();

            $table->text('text');
            $table->enum('access', [PostType::PRIVATE, PostType::PUBLIC])->default(PostType::PRIVATE);
            $table->integer('interested')->index()->nullable();
            $table->integer('clickthroughs')->index()->nullable();
            $table->integer('shows')->index()->nullable();
            $table->integer('reaction_count')->index()->nullable();
            $table->boolean('is_ppv')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->integer('ppv_price')->nullable();
            $table->timestamp('visible_after')->nullable();
            $table->timestamp('visible_until')->nullable();

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
        Schema::dropIfExists('posts');
    }
}
