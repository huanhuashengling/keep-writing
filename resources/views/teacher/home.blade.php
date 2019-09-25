@extends('layouts.teacher')

@section('content')
<?php 
  $strArr = ["不积跬步，无以至千里。", "不积小流，无以成江海。", "百丈之台，起于垒土。", "锲而不舍，金石可镂。", "锲而舍之，朽木不折。", "水滴石穿", "绳锯木断", "合抱之木，生于毫末。", "千里之行，始于足下。"];
  $str = $strArr[rand(0, 8)];
 ?>
<div class="container">
        <h4 class="col-md-12"><span class="label label-default">{{$str}}</span></h4>
  <form class="form-inline col-xs-6">
    <div class="form-group">
        <label>选择打卡类型：</label>
        <select class="form-control" id="writing-type-selection">
          <?php foreach ($writingTypes as $key => $writingType): ?>
          <option value="{{$writingType->id}}"  {{($writingType->id == $selectedWritingTypesId)?'selected':''}}>{{$writingType->name}}</option>
          <?php endforeach ?>
        </select>
      </div>
    </form>

    <?php 
    // echo $selectedWritingDate;
    // echo $selectedWritingTypesId;
    // echo $selectedWritingDate;
          // dd($dateItem);
          ?>
  <form class="form-inline col-xs-6">
    <div class="form-group">
        <label>选择打卡时间：</label>
        <select class="form-control" id="writing-date-selection">
          <?php foreach ($writingDates as $key => $dateItem): ?>
          <option value="{{$dateItem['value']}}" {{$dateItem['selected']}}>{{$dateItem['label']}}</option>
          <?php endforeach ?>

        </select>
      </div>
    </form>
    
     <!-- <div class="row">
        <div class="col-md-12 col-xs-12">
          <label>全校打卡进度：</label>
            <div class="progress">
              <div class="progress-bar progress-bar-info progress-bar-striped" id="current-date-type-post-progress" role="progressbar" aria-valuenow="20"  aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                <span class="sr-only">20% Complete</span>
              </div>
            </div>
           </div>
        </div>-->
        <!-- <h4><span class="label label-default">钢笔字完成3天连续打卡</span></h4> -->
      <label class="" id="cheer-label">{{$cheerUpStr}}</label>

    <input id="posted-path" value="{{ @$post->storage_name }}" hidden />
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <!-- <div class="alert alert-success" style="padding-bottom: 5px; padding-top: 10px">
                  <h4>上传作业成</h4>
                </div> -->
              <!-- <h4>上传作业</h4> -->
              @if(Session::has('success'))
                <div class="alert alert-success" style="padding-bottom: 5px; padding-top: 10px">
                  <h5>{!! Session::get('success') !!}</h5>
                </div>
              @endif

              @if(Session::has('danger'))
                <div class="alert alert-danger" style="padding-bottom: 5px; padding-top: 10px">
                  <h5>{!! Session::get('danger') !!}</h5>
                </div>
              @endif

              {!! Form::open(array('url'=>'teacher/upload','method'=>'POST', 'files'=>true)) !!}
                <input type="hidden" name="writing_types_id" id="writing_types_id" value="{{$selectedWritingTypesId}}">
                <input type="hidden" name="writing_date" id="writing_date" value="{{$selectedWritingDate}}">
                {!! Form::file('source', ['id' => 'input-zh']) !!}
              {!! Form::close() !!}
            </div>
        </div>
        

</div>
@endsection

@section('scripts')
    <link href="/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <script src="/js/fileinput.min.js"></script>
    <script src="/js/locales/zh.js"></script>
    <script src="/js/teacher/upload.js?v={{rand()}}"></script>
@endsection