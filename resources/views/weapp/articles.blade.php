@extends('layouts/app')
@section("title","文章管理1")
@section("content")
    <div class="row">
        <div>
            <button><a href="{{ url('articles/create') }}">发布文章</a></button>
            {{--            <button><a target="_blank" href="{{ env("ARTICLE_WRITE") }}">发布文章</a></button>--}}
            @foreach($articles as $article)
                @include('shared/_article')
            @endforeach

        </div>
        {{ $articles->links() }}
    </div>

@endsection
<style>  /*  样式一定要写在endsection外边。。。*/
    .col-xs-12 {
        padding-top: 10px;
        text-align: left;
        width: 50%;
        height: auto;
        float: left;
        background-color: white;
        margin: 30px 1px 0px 0px;
        box-shadow: #2ab27b;
        list-style: none;
    }

</style>
<script>
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }

    console.log(getQueryString('test'));
</script>