<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxminiprogramAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wxminiprogram_audit', function (Blueprint $table) {

            $table->comment = '微信小程序提交审核的小程序';
            $table->increments('id');
            $table->string('appid')->comment('小程序appid');
            $table->string('auditid')->comment('审核编号');
            $table->tinyInteger('status')->nullable()->default(3)->comment('审核状态，其中0为审核成功，1为审核失败，2为审核中，3已提交审核');
            $table->string('reason')->default('')->comment('当status=1，审核被拒绝时，返回的拒绝原因');
            $table->timestamp('create_time')->comment('提交审核时间');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wxminiprogram_audit');
    }
}
