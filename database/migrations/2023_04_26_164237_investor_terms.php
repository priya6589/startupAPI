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
        Schema::create('investor_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('principal_residence',['0','1'])->default('0')->nullable();
            $table->enum('cofounder',['0','1'])->default('0')->nullable();
            $table->enum('prev_investment_exp',['0','1'])->default('0')->nullable();
            $table->enum('experience',['0','1'])->default('0')->nullable();
            $table->enum('net_worth',['0','1'])->default('0')->nullable();
            $table->enum('no_requirements',['0','1'])->default('0')->nullable();
            $table->enum('annual_income',['0','1'])->default('0')->nullable();
            $table->enum('accredited_net_worth',['0','1'])->default('0')->nullable();
            $table->enum('final_annual_networth',['0','1'])->default('0')->nullable();
            $table->enum('foriegn_annual_income',['0','1'])->default('0')->nullable();
            $table->enum('foriegn_net worth',['0','1'])->default('0')->nullable();
            $table->enum('body_corporates',['0','1'])->default('0')->nullable();
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
        //
    }
};
