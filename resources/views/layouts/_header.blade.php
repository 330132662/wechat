<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ env('APP_NAME') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                {{--通用导航--}}
                <li class=""><a href="{{ url('articles') }}">文章</a></li>
{{--                <li class=""><a href="{{ url("wechat/authlist") }}">授权列表</a></li>--}}
                <li class=""><a href="{{url("products")}}">产品</a></li>
                {{--管理员专属导航--}}
                @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->id==1)
                    <li class=""><a href="">管理模板</a></li>

                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li><a href="{{ url('login') }}">登录</a></li>
                    <li><a href="{{ env("AUTH_PALTFORM_URL").'register' }}">注册</a></li>
                @else
                    <li>
                        <a href="{{ url('/') }}">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>
                    </li>
                    {{-- 消息通知标记 --}}
                    <li>
                        <a href="{{ url('/') }}" class="notifications-badge" style="margin-top: -2px;">
                            <span class="badge badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'fade' }} "
                                  title="消息提醒">
                                {{ Auth::user()->notification_count }}
                            </span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="user-avatar pull-left" style="margin-right:8px; margin-top:-5px;">
                                <img src="{{ Auth::user()->avatar }}" class="img-responsive img-circle" width="30px"
                                     height="30px">
                            </span>
                            {{ Auth::user()->name }} [{{ Auth::id() }} ]<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">

                            @if(Auth::id()==10164)
                                <li>
                                    <a href="{{ url('users') }}">
                                        <span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>
                                        管理后台
                                    </a>
                                </li>
                            @endif

                            {{--<li>
                                <a href="{{ url('/') }}">
                                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                    个人中心
                                </a>
                            </li>--}}
                            {{--<li>
                                <a href="{{ url('/')}}">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    编辑资料
                                </a>
                            </li>--}}
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                                    退出登录
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;"> 退出登录
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>