@extends('layouts.teacher')

@section('content')

<div class="container" style="padding-left: 0px; padding-right: 0px">
    <!-- <div class="col-md-12 col-xs-12" style="margin-bottom: 10px">
        <div class="btn-group" role="group" aria-label="...">
            <button class="btn btn-info btn-sm" id="pen-posts-btn">钢笔字</button>
            <button class="btn btn-info btn-sm" id="chalk-posts-btn">粉笔字</button>
            <button class="btn btn-info btn-sm" id="brush-posts-btn">毛笔字</button>
        </div>
    </div> -->

    <div class="col-md-12 col-xs-12" style="margin-bottom: 10px">
        <div class="btn-group" role="group" aria-label="...">
            <button class="btn btn-info btn-sm" id="my-posts-btn">我的</button>
            <button class="btn btn-info btn-sm" id="same-subject-posts-btn">同学科</button>
            <!-- <button class="btn btn-info btn-sm" id="same-grade-posts-btn">同性别</button> -->
            
            <button class="btn btn-info btn-sm" id="my-marked-posts-btn">我点赞</button>
            <button class="btn btn-info btn-sm" id="most-marked-posts-btn">最多赞</button>
            <!-- <button class="btn btn-info btn-sm" id="has-comment-posts-btn">有评语</button> -->
            <button class="btn btn-info btn-sm" id="all-posts-btn">全部</button>
            <!-- <input type="text" name="" id="search-name" class="form-control input-sm" placeholder="姓名"> -->
            <!-- <button class="btn btn-info btn-sm" id="name-search-btn">搜索</button> -->
            <button class="btn btn-default btn-sm">{{ $posts->lastPage() }}页{{ $posts->total() }}份</button>
            <!-- <button class="btn btn-default btn-sm"></button> -->
        </div>
    </div>
    <div class="col-md-12 col-xs-12" id="posts-list">
    @foreach($posts as $key=>$post)
        @php
            $post_storage_name = "posts/" . $schoolCode . "/" .$post->storage_name;
            $writeDate = substr($post->writing_date, 4, 2) . "月" . substr($post->writing_date, 6, 2);
            $writingType = $post->writing_type_name;
            if ($post->mark_num) {
                $markstr = $post->mark_num . "赞";
            } else {
                $markstr = "";
            }
        @endphp
        <div class="col-md-2 col-sm-4 col-xs-6" style="padding-left: 5px; padding-right: 5px;">
            <div class="alert alert-info" style="padding: 10px; text-align: center">
                <!--<div class="text-center"><img height="140px" value="{{ $post['pid'] }}" src="/imager?src={{$post_storage_name}}"></div>-->

                <img class="img-responsive thumb-img center-block" value="{{ $post['pid'] }}" src="{{ getThumbnail($post->storage_name, 120, 170, $schoolCode, 'fit', $post->file_ext) }}" alt="">
                <div><h5 style="margin-top: 5px; margin-bottom: 5px; text-align: center"><small>{{ $post->username }} {{$writeDate}} {{$writingType}} {{ $markstr}}</small></h5>  </div>
            </div>
        </div>
    @endforeach
    {{ $posts->appends(Illuminate\Support\Facades\Input::except('page'))->links('pagination.limit_links') }}
    </div>
    <!--{!! $posts->render() !!}-->
</div>

<!-- Modal -->
<div class="modal fade" id="classmate-post-modal" tabindex="-1" role="dialog" aria-labelledby="classmatePostModalLabel">
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
        <div class="switch" id="switch-box">
            <input type="checkbox" id="like-check-box" name="likeCheckBox"/>
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