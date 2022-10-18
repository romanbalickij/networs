<?php

use App\Enums\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->unique();
            $table->string('location')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('background')->nullable();
            $table->string('url')->nullable();
            $table->string('locale')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('business_address')->nullable();
            $table->string('tax_number')->nullable();
            $table->enum('role', [UserType::USER, UserType::ADMIN])->default(UserType::USER);
            $table->enum('activity_status', [UserType::ACTIVE, UserType::BUSY, UserType::INACTIVE])->default(UserType::ACTIVE);
            $table->text('description')->nullable();
            $table->integer('balance')->nullable();
            $table->integer('count_subscribers')->nullable();
            $table->boolean('verified')->default(true);
            $table->boolean('blocked')->default(false);
            $table->boolean('is_online')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('confirmed_code')->nullable();
            $table->integer('referral_link_id')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
