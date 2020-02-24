<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->double('balance')->default(0);
            $table->double('total_earning')->default(0);
            $table->double('pending_budget')->default(0);
            $table->dateTime('next_withdraw_date')->nullable();
            $table->double('rating')->default(0.0);
            $table->integer('jobs_done')->default(0);
            $table->integer('rehired')->default(0);
            $table->integer('global_rank')->default(0);
            $table->integer('job_success')->default(0);
            $table->integer('recommendation')->default(0);
            $table->integer('on_time')->default(0);
            $table->integer('on_budget')->default(0);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('account_type', ['freelancer', 'client'])->nullable();
            $table->enum('current_account', ['freelancer', 'client'])->nullable();
            $table->boolean('verified')->default(false);
            $table->string('status')->default('online');
            $table->integer('credit')->default(30);
            $table->dateTime('next_reset_date')->default(Carbon::now()->add(1, 'month'));
            $table->integer('hourly_rate')->default(5);
            $table->text('skills')->nullable();
            $table->decimal('spent')->default(0);
            $table->string('tagline')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->longText('presentation')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
