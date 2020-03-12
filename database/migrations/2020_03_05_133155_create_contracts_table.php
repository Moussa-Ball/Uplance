<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('type');
            $table->double('rate')->nullable();
            $table->double('work_hours')->default(0);
            $table->double('remaining')->default(0);
            $table->double('project_paid')->default(0);
            $table->double('amount')->default(0);
            $table->double('budget')->default(0);
            $table->double('milestones_paid')->default(0);
            $table->double('total_earnings')->default(0);
            $table->date('due_date')->nullable();
            $table->text('milestones')->nullable();
            $table->boolean('completed')->default(false);
            $table->unsignedBigInteger('to_id');
            $table->unsignedBigInteger('from_id');
            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('contracts');
    }
}
