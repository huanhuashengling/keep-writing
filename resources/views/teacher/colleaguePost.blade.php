@extends('layouts.teacher')

@section('content')

<div class="container" style="padding-left: 0px; padding-right: 0px">
    <input type="hidden" name="" id="wtId" value="{{$tWritingTypesId}}">
      

    <div class="col-md-12 col-xs-12">
        <!-- <div class="form-group col-md-3 col-xs-3">
        <h4>{{$writingTypeName}}</h4>
      </div> -->
      <div class="form-group col-md-3 col-xs-3">
        <button class="btn {{("my" == $getDataType)?"btn-info":"btn-default"}} btn-sm" id="my-posts-btn">我的 <span class="badge">{{("my" == $getDataType)?$totalDesc:""}}</span></button>
      </div>
      <div class="form-group col-md-3 col-xs-3">
        <button class="btn {{("all" == $getDataType)?"btn-info":"btn-default"}} btn-sm" id="all-posts-btn">全校 <span class="badge">{{("all" == $getDataType)?$totalDesc:""}}</span></button>
      </div>
      <!-- <div class="form-group col-md-5 col-xs-5 col-xs-offset-1">
        <div class="input-group">
        <input type="text" class="form-control input-sm">

        <span class="input-group-btn">
        <button class="btn btn-default btn-sm" type="button">
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        </button>
        </span>
        </div>
      </div> -->
      <!-- <div class="form-group col-md-8 col-xs-8"> -->
        <!-- <label>{{$totalDesc}}</label> -->
      <!-- </div> -->
      <!-- <div class="form-group col-md-3 col-xs-3 col-xs-offset-1">
        <input type="text" name="" id="search-name" style="width: 120px" class="form-control input-sm" placeholder="姓名">
      </div>
      <div class="form-group col-md-2 col-xs-2">
        <button class="btn btn-success btn-sm" id="name-search-btn">搜索</button>
      </div> -->
      
    </div>
    <div class="col-md-12 col-xs-12" id="posts-list" style="padding: 5px">
    @foreach($posts as $key=>$post)
        @php
            $writeDate = substr($post->writing_date, 4, 2) . "月" . substr($post->writing_date, 6, 2);
            $markStr = isset($post->mark_num)?$post->mark_num ."赞":"";
            $rateStr = isset($post->rate)?$post->rate ."星":"";
            $descStr = "";
            if ("my" == $getDataType) {
                $descStr = $writeDate . " " . $rateStr . " " .  $markStr;
            } else {
                $descStr = $post->username . " " . $writeDate . " " . $rateStr . " " .  $markStr;
            }
            $prevId = "";
            $nextId = "";
            if (isset($posts[$key-1])) {
              $prevId = $posts[$key-1]['pid'];
            }
            if (isset($posts[$key+1])) {
              $nextId = $posts[$key+1]['pid'];
            }
            
        @endphp

        @if ("普通话" == $post->writing_type_name)
            <div class="col-md-3 col-sm-6 col-xs-12" style="padding-left: 5px; padding-right: 5px;">
                <div class="alert alert-info" style="padding: 10px; text-align: center">
                    <audio controls src="{{ $baseUrl }}{{$post['export_name'] }}" >
                    Your browser does not support the audio element.
                    </audio>
                    <div><h5 style="margin-top: 5px; margin-bottom: 5px; text-align: center"><small>{{ $descStr }}</small></h5>  </div>
                </div>
            </div>
        @else
            <div class="col-md-2 col-sm-4 col-xs-6" style="padding-left: 5px; padding-right: 5px;">
                <div class="alert alert-info" style="padding: 10px; text-align: center">
                    <!-- /posts/yuying3/{{$post->post_code}}_c.{{$post->cover_ext}} -->
                    <!-- {{ getThumbnail($post->post_code, 121, 171, $schoolCode, 'background', $post->file_ext) }} -->
                    <img class="img-responsive thumb-img center-block" id="img{{ $post['pid'] }}" value="{{ $post['pid'] }}" prevId="{{$prevId}}" nextId="{{$nextId}}" src="{{$baseUrl . $post->post_code . '_c.png'}}" alt="" filePath="{{$baseUrl . $post->export_name}}">
                
                    <div><h5 style="margin-top: 5px; margin-bottom: 5px; margin-left: 0px; text-align: center"><small>{{ $descStr }}</small></h5>  </div>
                </div>
            </div>
        @endif
    @endforeach
    @if (count($posts) >0)
        <div class="col-md-12 col-xs-12">{{ $posts->appends(request()->input())->links() }}</div>
    @endif
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="colleague-post-modal" tabindex="-1" role="dialog" aria-labelledby="classmatePostModalLabel">
<input type="hidden" id="posts-id" value="">
<input type="hidden" id="mark-num" value="">
<input type="hidden" id="is-init" value="true">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="classmate-post-modal-label"></h4>
      </div>
      <div class="modal-body" style="padding-bottom: 0px; text-align: center;">
        <img src="" id='classmate-post-show' class="img-responsive img-thumbnail center-block" >
        <!-- <a href="" id="classmate-post-download-link">右键点击下载</a> -->
        <label id="post-comment"></label>
      </div>

    <div class="modal-footer">
      <div>
        <button class="btn btn-info" style="float: left;" id="prev-btn">上一张</button>
        <button class="btn btn-info" style="float: right;" id="next-btn">下一张</button>
      </div>
        <div>
          <!-- <a href="" class="btn btn-success  col-xs-offset-1" style="float: left;" id="post-download">下载</a> -->
          <div class="switch text-center" id="switch-box">
              <input type="checkbox" id="like-check-box" name="likeCheckBox"/>
          </div>
        </div>

    </div>
  </div>
</div>
</div>

@endsection

@section('scripts')
    <!-- <script type="text/javascript" src="/scratch/swfobject.js"></script> -->
    <link href="/css/bootstrap-switch.css" rel="stylesheet">
    <script src="/js/bootstrap-switch.min.js"></script>
    <script src="/js/teacher/colleague-post.js?v={{rand()}}"></script>
@endsection