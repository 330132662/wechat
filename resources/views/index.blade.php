@extends('layouts/app')
@section('title','首页')
@section("content")
    <div class="row">


        <div>
            @if(0)
                @if(\Illuminate\Support\Facades\Auth::check())
                    @include('shared/_weapp')
                @else
                    请登录
                @endif
            @else
                @include('shared/_weapp')
            @endif
        </div>
    </div>

@endsection()
<style>
    .col-xs-12 {
        padding-top: 30px;
        text-align: center;
        width: 50%;
        height: 200px;
        float: left;
        background-color: white;
        margin: 30px 1px 0 0;
        box-shadow: #2ab27b;
        list-style: none;
    }
</style>
<script>


    function pub() {
        alert('正在开发');
    }

</script>