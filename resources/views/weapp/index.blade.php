@extends('layouts/app')
@section('title','已授权我们的公众平台用户')
@section("content")
    <div class="row">


        @if(!empty($mp))

            @foreach($mp['list'] as $app)
                <li class="col-xs-12">
                    <div class="col-lg-3">
                        <span>ID {{ $app["authorizer_appid"] }}</span>
                        <input hidden value="{{ $app["authorizer_appid"] }}" id="appid">
                        <span> 授权时间  </span>
                    </div>
                    {{--<div class="col-lg-6">--}}
                    {{--<p>小程序名称：{{ $app->apptitle }}</p>--}}
                    {{--发布状态：@if($app->status==1)--}}
                    {{--未发布--}}
                    {{--@elseif($app->status==2)--}}
                    {{--发布审核中--}}
                    {{--@elseif($app->status==3)--}}
                    {{--审核通过已发布--}}
                    {{--@elseif($app->status==4)--}}
                    {{--审核未通过--}}
                    {{--@else--}}
                    {{--未知状态--}}
                    {{--@endif--}}


                    {{--<button onclick="pub()">发布</button>--}}
                    {{--</div>--}}
                    <div class="col-lg-3">
                        <p>
                            {{--<button><a href="{{ url('weapps/'.$app->id.'/edit') }}">管理小程序</a></button>--}}
                        </p>
                        <p>
                            <a href="{{ url('wechat/templist?appid='.$app["authorizer_appid"]) }}">选择模板</a>
                        </p>
                        <p>
                            <button onclick="pub()">下载二维码</button>
                            <a href="{{ route('wechat/commit').'?appid='.$app["authorizer_appid"] }}">审核与发布</a>
                        </p>
                    </div>

                </li>

            @endforeach

        @else
            <p>暂无小程序，请点击创建小程序</p>
        @endif

    </div>

    <script>

        function pub() {
            alert('开发中');
        }

        function submitAudit() {
            axios({
                method: "post",
                url: "/api/wechat/submitaudit",
                data: {
                    appid: document.getElementById('appid').value,
                }
            }).then(function (response) {
                console.log(response);
                const data = response.data;
                alert(data.errmsg);
                if (data.errorcode == 0) {

                } else {
                }


            })
                .catch(function (err) {
                    console.log(err);
                });
        }
    </script>

@endsection