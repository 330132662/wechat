<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '慧通小程序') -{{ env('APP_NAME') }} </title>
    <meta name="description" content="@yield('description', env('seo_description', ''))"/>
    <meta name="keyword" content="@yield('keyword', env('seo_keyword', ''))"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?628ccbc383dc5bde41ec9b0e2603fd06";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=65965709" charset="UTF-8"></script>
</head>

<body>
<div id="app">

    @include('layouts._header')

    <div class="container">

        @yield('content')

    </div>

    @include('layouts._footer')
</div>

@if (app()->isLocal())
    {{--        @include('sudosu::user-selector')--}}
@endif

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}">


</script>

<script>


</script>
@yield('scripts')
</body>
</html>