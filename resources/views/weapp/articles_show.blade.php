@extends('layouts/app')
@section("title",  $article->name)

@section("content")
    <div class="container-fluid">
        <p>
        <h1>{{ $article->name }}</h1></p>
        <div id="div"> <?php echo $article->content ?> </div>

    </div>
    <script>
        // const dobj = document.getElementById("div");
        // dobj.innerHTML = "<p>我是HTML代码</p>";
        {{--dobj.innerHTML = "{{ $article->content }}";--}}

    </script>
@endsection