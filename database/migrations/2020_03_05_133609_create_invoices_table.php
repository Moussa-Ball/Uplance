<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order');
            $table->string('issued');
            $table->string('description');
            $table->string('type');
            $table->string('rate')->nullable();
            $table->double('hours')->default(0);
            $table->double('amount')->default(0);
            $table->string('service_fee')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('payer_id')->nullable();
            $table->double('total')->default(0);
            $table->double('total_due')->default(0);
            $table->date('paid_at')->nullable();;
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('to_id')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('from_id');
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
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
        Schema::dropIfExists('invoices');
    }
}
