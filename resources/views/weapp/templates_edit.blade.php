@extends("layouts/app")
@section("title","管理模板")

@section("content")
    <div class="container-fluid">
        <form method="post" action="{{ url("templates") }}">

            <div class="col-lg-12">

                <div class="col-lg-2">名称</div>
                <div class="col-lg-10"><input name="name" placeholder="模板名"/></div>
            </div>
            <div class="col-lg-12">
                <div class="col-lg-2">封面</div>
                <div class="col-lg-10"><input width="100%" name="img" placeholder="封面url"
                                              value="http://g.hiphotos.baidu.com/image/h%3D300/sign=a674c1340f3b5bb5a1d726fe06d2d523/a6efce1b9d16fdfa21110671b88f8c5494ee7b34.jpg"/>
                </div>
            </div>
            {{ csrf_field() }}
            <input type="submit" value="提交">
        </form>
    </div>
@endsection