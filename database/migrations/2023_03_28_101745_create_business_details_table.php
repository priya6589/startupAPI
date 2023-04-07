<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('business_name');
            $table->string('reg_businessname')->nullable();
            $table->string('website_url')->nullable();
            $table->string('stage')->nullable();
            $table->string('department')->nullable();
            $table->string('startup_date')->nullable();
            $table->text('description')->nullable();
            $table->enum('primary_residence',['1','0'])->default('0');
            $table->enum('prev_experience',['1','0'])->default('0');
            $table->enum('experience',['1','0'])->default('0');
            $table->enum('cofounder',['1','0'])->default('0');
            $table->string('logo')->nullable();
            $table->enum('none_select',['1','0'])->default('0');
            $table->enum('kyc_purposes',['1','0'])->default('0');
            $table->string('tagline')->nullable();
            $table->string('sector')->nullable();
            $table->string('business_file')->nullable();
        
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
        Schema::dropIfExists('business_details');
    }
}
