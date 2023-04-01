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
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('gender');
            $table->string('city');
            $table->string('country');
            $table->string('linkedin_url')->nullable();
            $table->string('status')->default('active');
            $table->string('role')->default('user');
            $table->string('approval_status')->default('pending');
            $table->string('proof1_no')->nullable();
            $table->string('proof1_pic')->nullable();
            $table->string('proof2_no')->nullable();
            $table->string('proof2_pic')->nullable();
            $table->string('profile_pic')->nullable();
            $table->text('profile_desc')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

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
