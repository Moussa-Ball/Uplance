<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contract_title');
            $table->text('milestones')->nullable();
            $table->unsignedDecimal('hourly_rate')->nullable();
            $table->unsignedDecimal('total_amount')->nullable();
            $table->string('offer_type');
            $table->boolean('accepted')->nullable();
            $table->longText('description');
            $table->unsignedBigInteger('proposal_id')->nullable();
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            $table->unsignedBigInteger('to_id')->nullable();
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('from_id')->default(\Auth::id());
            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
