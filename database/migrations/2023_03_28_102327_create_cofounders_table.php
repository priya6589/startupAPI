<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCofoundersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cofounders', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');
            $table->string('username');
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
            // $table->string('proof1_no')->nullable();
            // $table->string('proof1_pic')->nullable();
            // $table->string('proof2_no')->nullable();
            // $table->string('proof2_pic')->nullable();
            $table->string('pic')->nullable();
            $table->text('profile_desc')->nullable();
            $table->boolean('terms_condition')->default(false);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            // $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('business_id')->references('id')->on('business_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cofounders');
    }
}
