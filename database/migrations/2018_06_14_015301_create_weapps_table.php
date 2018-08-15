<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeappsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weapps', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string("name")->default("未命名模板");
            $table->integer("status")->default(1)->comment("发布状态1未发布，2审核中，3审核通过，4审核驳回");
            $table->longText("img")->comment("封面URL");
            $table->integer("uid")->comment("创建人");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weapps');
    }
}
