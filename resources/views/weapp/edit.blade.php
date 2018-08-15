<body onload="getUrl()">
@extends('layouts/app')
@section('title','小程序管理')
@section("content")
    <div class="row">
        <form action="{{ route('weapps.update',$app->id) }}" method="post">
            小程序名称： <input id="nav" name="apptitle" title=""
                          value="{{ $app->apptitle }}">
            <br>
            <p>
                @if($authorizer !=null)
                    <a id="auth" target="_blank">更新授权</a>

                    <a href="{{ url('wechat/templist?appid='.$app["appid"]) }}">选择模板</a>
                    {{--<a href="{{ url('wechat/commit?appid='.$app["appid"]) }}">审核与发布</a>--}}
                @else
                    您还未绑定小程序，请
                    <a id="auth" target="_blank">授权绑定小程序</a>

                @endif


            </p>
            小程序主页宣传视频: <textarea id="nav" style="margin-top: 20px" name="homevideo" title="请先上传到第三方网站后将播放链接粘贴到这儿"
                                 placeholder="请先上传到第三方网站后将播放链接粘贴到这儿"
                                 value="{{ $app->homevideo }}">{{ $app->homevideo }}</textarea>
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div id="nav">导航栏文本
                @if($app!=null && is_array(json_decode($app->nav)))
                    @foreach(json_decode($app->nav) as $title)
                        <input id="nav" style="margin-top: 20px" name="nav[]" placeholder="导航标题" value="{{ $title }}"
                               maxlength="4"/> ,
                    @endforeach
                @else
                    <input id="nav" style="margin-top: 20px" name="nav[]" placeholder="导航标题" maxlength="4"/> ,
                    <input id="nav" style="margin-top: 20px" name="nav[]" placeholder="导航标题" maxlength="4"/> ,
                    <input id="nav" style="margin-top: 20px" name="nav[]" placeholder="导航标题" maxlength="4"/> ,
                    <input id="nav" style="margin-top: 20px" name="nav[]" placeholder="导航标题" maxlength="4"/> ,
                @endif


            </div>
            <div>
                公司名<input class="input-group" id="company" value="{{ $app->company }}" name="company">
                地址<textarea class="input-group" id="addr" name="addr">{{ $app->addr }}</textarea>
                联系电话<input class="input-group" id="tel" value="{{ $app->tel }}" name="tel">
                公司简介<textarea class="input-group" id="introduce" value=""
                              name="introduce">{{ $app->introduce }}</textarea>
            </div>
            <p></p>

            {{--<input type="file"  name="fileToUpload" id="fileToUpload" onchange="fileSelected();">--}}
            <input type="submit" class="btn-info" value="保存"/>
        </form>

        @if($authorizer !=null)

            绑定信息：
            <div>名称：{{ $authorizer['nick_name']  }} </div>
            <div>账户类型：
                @if($authorizer['service_type_info']['id']==0)
                    订阅号
                @elseif($authorizer['service_type_info']['id']==1)
                    由历史老帐号升级后的订阅号
                @elseif($authorizer['service_type_info']['id']==2)
                    服务号
                @endif
            </div>
            <div>认证状态：
                @switch($authorizer['verify_type_info']['id'])
                    @case(-1):
                    未认证
                    @break;
                    @case(0):
                    微信认证
                    @break;
                    @case(1):
                    新浪微博认证
                    @break;
                    @case(2):
                    腾讯微博认证
                    @break;
                    @case(3):
                    已资质认证通过但还未通过名称认证
                    @break;
                    @case(4):
                    已资质认证通过、还未通过名称认证，但通过了新浪微博认证
                    @break;
                    @case(5):
                    已资质认证通过、还未通过名称认证，但通过了腾讯微博认证
                    @break;

                @endswitch
            </div>
            <div>
                @if(array_key_exists('head_img',$authorizer))
                    <img src="{{ $authorizer['head_img']}}">



                @endif</div>
            <div>扫码关注 <img style="height: 300px ;width: 300px;" src="{{ $authorizer['qrcode_url']}}"></src> </div>

        @endif
    </div>
    <script>
        /*  获取授权url  */
        function getUrl() {
            axios({
                method: "get",
                url: "/api/getAuthUrl",
                data: {}
            }).then(function (data) {
                console.log(data);

                if (data.status == 200) {
                    document.getElementById('auth').setAttribute('href', data.data);
                } else {
                    alert('获取授权url失败');
                }

            })
                .catch(function (err) {
                    console.log(err);
                });
        }


    </script>
    <style>
    </style>
@endsection
</body>