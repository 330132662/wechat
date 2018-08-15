<?php

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
            $table->integer('user_id');
            $table->primary('user_id');
            $table->string('username', 50)->default('')->nullable();
            $table->string('job', 20)->default('')->nullable();
            $table->string('name', 50);
            $table->string('avatar', 191)->default('');
            $table->char('gender', 1)->nullable();
            $table->date('birthday')->nullable();
            $table->char('country', 2)->default('ZH')->nullable();
            $table->char('province', 6)->default('')->nullable();
            $table->char('city', 6)->default('')->nullable();
            $table->string('timezone', 30)->default('PRC')->nullable();
            $table->string('locale', 15)->default('zh')->nullable();
            $table->string('mobile', 11)->nullable()->default('');
            $table->boolean('mobile_validated')->nullable()->default(0);
            $table->string('email')->default('')->nullable()->default('');
            $table->boolean('email_validated')->nullable()->default(0);
            $table->timestamp('email_validated_at')->nullable();
            $table->boolean('name_validated')->nullable()->default(0);
            $table->boolean('status')->default(0);
            $table->string('api_token', 100);
            $table->string('team', 255)->default("");// 2018年5月18日10:07:47 新增，用于新项目
            $table->smallInteger('source')->default(0)->nullable();
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
        Schema::dropIfExists('users');
    }
}
