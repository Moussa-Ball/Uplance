<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('paypal')->default(false);
            $table->boolean('credit_card')->default(false);
            $table->enum('default_method', ['paypal', 'credit_card'])->nullable();
            $table->dateTime('paypal_activation_date')->default(\Carbon\Carbon::now()->addDay(2))->nullable();
            $table->dateTime('credit_card_activation_date')->default(\Carbon\Carbon::now()->addDay(2))->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('withdraw_methods');
    }
}
