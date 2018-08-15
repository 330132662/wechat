@extends('layouts/app')
@section('title','用户列表')
@section("content")
    <div class="row">
        <div>
            @if(!empty($users))
                {{--<input id="pass" placeholder="仅可修改自己测试号的密码严禁随意重置他人密码">--}}
                @foreach($users as $app)
                    <li class="col-xs-12">
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-6">
                            ID: {{ $app->user_id }}
                            用户名：{{ $app->name }}
                            邮箱：{{ $app->email }}
                            {{--<button onclick="reset({{ $app->id }})">重置</button>--}}
                        </div>

                    </li>

                @endforeach
                {{ $users->links() }}
            @else
                <p>暂无小程序，请点击创建小程序</p>
            @endif</div>

    </div>
    <script>
        function reset(uid) {

            var password = document.getElementById('pass').value;
            console.log(password);
            axios({
                method: "post",
                url: "/password/reset",
                data: {
                    password: password,
                    uid: uid
                }
            }).then(function (data) {
                console.log(data);
                if (data.status == 200) {
                    // window.location = '/articles';
                    // console.log(data.data);
                }

            })
                .catch(function (err) {
                    console.log(err);
                });


        }

    </script>
@endsection