@extends('layouts.teacher')

@section('content')
<input type="hidden" name="" id="base-url" value="{{$baseUrl}}">
<div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title" id="video-label">硬笔书法偏旁部首，月字旁</h3>
      </div>
      <div class="panel-body">
        <video
            id="resources-player"
            class="video-js vjs-big-play-centered"
            controls
            preload="auto"
            poster=""
            data-setup='{}'
            width='350' height=''>
          <source src="{{$baseUrl}}/downloads/硬笔书法偏旁部首，月字旁.mp4" type="video/mp4"></source>
          <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a
            web browser that
            <a href="https://videojs.com/html5-video-support/" target="_blank">
              supports HTML5 video
            </a>
          </p>
        </video>
      </div>
    </div>

<nav class="navbar navbar-default navbar-fixed-bottom" style="padding-top: 0px">
    <ul class="nav nav-tabs nav-justified">
      <li role="presentation" class="dropdown">
        <a class="dropdown-toggle col-xs-4" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
          钢笔字 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <?php foreach ($penResources as $key => $resource): ?>
            <li><a href="#" class="penItem" filename="{{$resource->filename}}">{{$resource->label}}</a></li>
          <?php endforeach ?>
        </ul>
      </li>

      <li role="presentation" class="dropdown">
        <a class="dropdown-toggle col-xs-4" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
          粉笔字 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <?php foreach ($chalkResources as $key => $resource): ?>
            <li><a href="#" class="chalkItem" filename="{{$resource->filename}}">{{$resource->label}}</a></li>
          <?php endforeach ?>
        </ul>
      </li>

      <li role="presentation" class="dropdown">
        <a class="dropdown-toggle col-xs-4" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
          毛笔字 <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <?php foreach ($brushResources as $key => $resource): ?>
            <li><a href="#" class="brushItem" filename="{{$resource->filename}}">{{$resource->label}}</a></li>
          <?php endforeach ?>
        </ul>
      </li>
    </ul>
</nav>
@endsection

@section('scripts')
    <link href="//vjs.zencdn.net/7.3.0/video-js.min.css" rel="stylesheet">
    <script src="//vjs.zencdn.net/7.3.0/video.min.js"></script>
    <script src="/js/teacher/resources.js?v={{rand()}}" type="text/javascript"></script>
    <style type="text/css">
      .vjs-paused .vjs-big-play-button,
      .vjs-paused.vjs-has-started .vjs-big-play-button {
          display: block;
      }
      .video-js .vjs-big-play-button{
    font-size: 2.5em;
    line-height: 2.3em;
    height: 2.5em;
    width: 2.5em;
    -webkit-border-radius: 2.5em;
    -moz-border-radius: 2.5em;
    border-radius: 2.5em;
    background-color: #73859f;
    background-color: rgba(115,133,159,.5);
    border-width: 0.15em;
    margin-top: -1.25em;
    margin-left: -1.75em;
}
/* 中间的播放箭头 */
.vjs-big-play-button .vjs-icon-placeholder {
    font-size: 1.63em;
}
/* 加载圆圈 */
.vjs-loading-spinner {
    font-size: 2.5em;
    width: 2em;
    height: 2em;
    border-radius: 1em;
    margin-top: -1em;
    margin-left: -1.5em;
}
.video-js.vjs-playing .vjs-tech {
    pointer-events: auto;
}
.video-js .vjs-time-control{display:block;}
.video-js .vjs-remaining-time{display: none;}
    </style>
@endsection