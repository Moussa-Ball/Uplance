<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('rated');
            $table->boolean('on_time')->nullable();
            $table->boolean('on_budget')->nullable();
            $table->longText('comment')->nullable();
            $table->decimal('rating')->default(0);
            $table->unsignedBigInteger('to_id');
            $table->unsignedBigInteger('from_id');
            $table->unsignedBigInteger('contract_id');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
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
        Schema::dropIfExists('reviews');
    }
}
