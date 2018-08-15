
<a href="{{ url("templates") }}">
    <button class="btn-default">创建小程序</button>
</a>
@if(!empty($apps))

    @foreach($apps as $app)
        <li class="col-xs-12">
            <div class="col-lg-3">
                <img class="img-circle" width="150px" height="150px"
                     src=""/>
            </div>
            <div class="col-lg-6">
                <p>小程序名称：{{ $app->apptitle }}</p>
            </div>
            <div class="col-lg-3">
                <p>
                    <button><a href="{{ url('weapps/'.$app->id.'/edit') }}">管理小程序</a></button>
                </p>
                <p>
                    <button onclick="pub()">查看数据</button>
                </p>
                <p>
                    <button onclick="pub()">下载二维码</button>
                </p>
            </div>

        </li>

    @endforeach
    {{ $apps->links() }}
    @endif