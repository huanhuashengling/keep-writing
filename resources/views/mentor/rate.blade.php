@extends('layouts.mentor')

@section('content')

<div class="container">
        请选择类型：
            <?php foreach ($writingTypes as $key => $writingType): ?>
                    <button class="btn writing-type-btn" value="{{$writingType->id}}"> {{$writingType->name}}</button>
            <?php endforeach ?>
        <input type="hidden" name="" id="selected-writing-types-id">
        <div class="row">
            <div id="teacher-list"></div>
        </div>
        <div class="row">
            <div id="post-list"></div>
        </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<input type="hidden" id="posts-id" value="">

  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header" style="padding-bottom: 0px;padding-top: 5px">
            <div class="text-center col-md-5 col-md-offset-3">
                <input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="1">
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
      <div class="modal-body">
        <input type="hidden" name="" id="selected-posts-id">
        <input type="hidden" name="" id="selected-teachers-id">
        <img src="" id='post-show' class="img-responsive img-thumbnail center-block">
      </div>
  </div>
</div>
</div>

@endsection

@section('scripts')
    <script src="/js/mentor/rate.js?v={{rand()}}"></script>
    <script src="/js/star-rating.min.js"></script>
    <link href="/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection