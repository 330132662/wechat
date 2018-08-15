@extends('layouts/app')
@section('title','第三方平台的代码模板')
@section("content")
    <div class="row">
        @if( $templates!=null)
            @foreach($templates as $t)
                @include('shared/_template')

            @endforeach

        @endif
        <a onclick="toCommit()">审核与发布</a>
    </div>

@endsection
<script>

    function toCommit() {
        const appid = getQueryString('appid');
        window.location = '/wechat/commit?appid=' + appid;
    }

    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }

</script>