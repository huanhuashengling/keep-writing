@extends('layouts.teacher')

@section('content')

<div class="container">
  <form class="form-inline col-xs-6">
    <div class="form-group">
        <label>选择书写类型：</label>
        <select class="form-control" id="writing-type-selection" disabled>
          <?php foreach ($writingTypes as $key => $writingType): ?>
          <option value="{{$writingType->id}}">{{$writingType->name}}</option>
          <?php endforeach ?>
        </select>
      </div>
    </form>
    <?php 
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
    <input id="posted-path" value="{{ @$post->storage_name }}" hidden />
    <div class="row">
      <div class="col-md-12 col-xs-12">
              <!-- <h4>上传作业</h4> -->
              @if(Session::has('success'))
                <div class="alert alert-success">
                  <h4>{!! Session::get('success') !!}</h4>
                </div>
              @endif

              @if(Session::has('danger'))
                <div class="alert alert-danger">
                  <h4>{!! Session::get('danger') !!}</h4>
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
    <script src="/js/teacher/upload.js"></script>
@endsection