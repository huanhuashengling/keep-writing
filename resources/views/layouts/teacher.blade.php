<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/img/oic.ico" type="image/x-icon" />
    <title>{{ trans("layouts.title") }}</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/jquery-ui.css" rel="stylesheet">
    <!-- <link href="/css/all.css" rel="stylesheet"> -->
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/respond.min.js"></script>

    <script src="/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <script src="/js/plugins/sortable.min.js" type="text/javascript"></script>
    <script src="/js/plugins/purify.min.js" type="text/javascript"></script>
    <script src="/js/bootstrap3-typeahead.min.js"></script>

    
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/teacher') }}">
                    {{ trans("layouts.project_name") }}
                    @if (!Auth::guard("teacher")->guest())
                    -<small>{{ Auth::guard("teacher")->user()->username }}</small>
                    @endif
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Authentication Links -->
                   @if (Auth::guard("teacher")->guest())
                        <!-- <li><a href="{{ url('/login') }}">{{ trans("layouts.login") }}</a></li> -->
                    @else
                        <!-- Left Side Of Navbar -->
                        <ul class="nav navbar-nav">
                            <li><a href="{{ url('/teacher') }}">Keep打卡</a></li>
                            <!-- <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">打卡记录 <span class="caret"></span></a>
                              <ul class="dropdown-menu">
                                <li><a href="{{ url('/teacher/posts?wtId=1') }}">钢笔字</a></li>
                                <li><a href="{{ url('/teacher/posts?wtId=2') }}">粉笔字</a></li>
                                <li><a href="{{ url('/teacher/posts?wtId=3') }}">毛笔字</a></li>
                                <li><a href="{{ url('/teacher/posts?wtId=4') }}">普通话</a></li>
                              </ul>
                            </li> -->

                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">打卡记录 <span class="caret"></span></a>
                              <ul class="dropdown-menu">
                                <li><a href="{{ url('/teacher/colleague?wtId=1') }}">钢笔字</a></li>
                                <li><a href="{{ url('/teacher/colleague?wtId=2') }}">粉笔字</a></li>
                                <li><a href="{{ url('/teacher/colleague?wtId=3') }}">毛笔字</a></li>
                                <!-- <li><a href="{{ url('/teacher/colleague?wtId=4') }}">普通话</a></li> -->
                              </ul>
                            </li>
                            <!-- <li><a href="{{ url('/teacher/mutual-evaluation') }}">作品互评</a></li> -->
                            <li><a href="{{ url('/teacher/resources') }}">学习资源</a></li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Hello,
                                    {{ Auth::guard("teacher")->user()->username }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <!-- <li><a href="{{ url('/teacher/info') }}"><i class="fa fa-btn fa-sign-out"></i>个人信息</a></li> -->
                                    <li><a href="{{ url('/teacher/reset') }}"><i class="fa fa-btn fa-sign-out"></i>修改密码</a></li>
                                    <li><a href="{{ url('/teacher/logout') }}"><i class="fa fa-btn fa-sign-out"></i>{{ trans("layouts.logout") }}</a></li>
                                </ul>
                            </li>
                        </ul>
                @endif
            </div>
        </div>
    </nav>

    @yield('content')
    @yield('scripts')
</body>
</html>