<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddField1ToWeappsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weapps', function (Blueprint $table) {
            $table->string('apptitle')->comment("主页TITLE");// 2018年6月17日14:40:15
            $table->longText('homevideo')->comment("主页视频地址");// 2018年6月17日14:40:15

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
