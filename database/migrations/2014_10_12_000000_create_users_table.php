<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('gender');
            $table->string('city');
            $table->string('country');
            $table->string('linkedin_url')->nullable();
            $table->text('profile')->nullable();
            $table->string('residence_worth')->nullable();
            $table->string('experience')->nullable();
            $table->string('proof1_no')->nullable();
            $table->string('proof1_pic')->nullable();
            $table->string('proof2_no')->nullable();
            $table->string('proof2_pic')->nullable();
            $table->string('profile_pic')->nullable();
            $table->text('profile_desc')->nullable();
            $table->boolean('kyc_purposes')->nullable();
            $table->enum('status',['active','inactive','deleted'])->default('active');
            $table->enum('role',['user','investor','founder'])->default('user');
            $table->enum('approval_status',['approved','pending','cencelled'])->default('pending');
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
};
