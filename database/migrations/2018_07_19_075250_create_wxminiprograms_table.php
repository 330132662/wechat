<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxminiprogramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wxminiprograms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户ID');
            $table->string('nick_name')->comment('微信小程序名称');
            $table->string('alias')->nullable()->comment('别名');
            $table->string('token')->nullable()->comment('平台生成的token值');
            $table->string('head_img')->nullable()->comment('微信小程序头像');
            $table->tinyInteger('verify_type_info')->nullable()->comment('授权方认证类型，-1代表未认证，0代表微信认证');
            $table->tinyInteger('is_show')->default(0)->comment('是否显示，0显示，1隐藏');
            $table->string('user_name')->comment('原始ID');
            $table->string('qrcode_url')->nullable()->comment('二维码图片的URL');
            $table->string('business_info')->nullable()->comment('json格式。用以了解以下功能的开通状况（0代表未开通，1代表已开通）： open_store:是否开通微信门店功能
             open_scan:是否开通微信扫商品功能 open_pay:是否开通微信支付功能 open_card:是否开通微信卡券功能 open_shake:是否开通微信摇一摇功能');
            $table->integer('idc')->nullable()->comment('idc');
            $table->string('principal_name')->nullable()->comment('小程序的主体名称');
            $table->string('signature')->nullable()->comment('帐号介绍');
            $table->string('miniprograminfo')->nullable()->comment('json格式。判断是否为小程序类型授权，包含network小程序已设置的各个服务器域名');
            $table->longText('func_info')->comment('json格式。权限集列表，ID为17到19时分别代表： 17.帐号管理权限
             18.开发管理权限 19.客服消息管理权限 请注意： 1）该字段的返回不会考虑小程序是否具备该权限集的权限（因为可能部分具备）。');
            $table->string('authorizer_appid')->nullable()->comment('小程序appid');
            $table->string('authorizer_access_token')->nullable()->comment('授权方接口调用凭据（在授权的公众号或小程序具备API权限时，才有此返回值），也简称为令牌');
            $table->integer('authorizer_expires')->nullable()->comment('refresh有效期');
            $table->string('authorizer_refresh_token')->nullable()->comment();
            $table->timestamp('create_time')->comment('授权时间');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wxminiprograms');
    }
}
