<a id="ok" class="col-xs-12">
    <div><strong><a href="{{ url('articles/'.$article->id) }}">{{ $article->name }}</a></strong></div>
    <div>author {{ $article->author_name }}</div>
    <div>in {{ $article->weapp }}</div>
    <div>类型：@if($article->type== 1)
            普通文章
        @else
            服务
        @endif
    </div>
</a>
