@extends('layouts.mentor')

@section('content')

<div class="container">
        请选择打卡时间：
            <?php foreach ($stageCheckData as $key => $stageCheck): ?>
                    <button class="btn btn-default writing-date-btn" value="{{$stageCheck['writing_date']}}"> {{$stageCheck["writing_date"]}} <span class="badge">{{$stageCheck["rate_num"]}} / {{$stageCheck["post_num"]}}</span></button>
            <?php endforeach ?>
        <input type="hidden" name="" id="selected-writing-date">
        <input type="hidden" name="" id="selected-writing-types-id" value="{{$writingTypesId}}">
        <div class="row">
            <div id="post-list"></div>
        </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<input type="hidden" id="posts-id" value="" />

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="text-center">
                    <input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="1" />
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="" id="selected-posts-id" />
                <input type="hidden" name="" id="selected-teachers-id" />
                <img src="" id='post-show' class="img-responsive img-thumbnail center-block" />
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="/js/mentor/stage.js?v={{rand()}}"></script>
    <script src="/js/star-rating.min.js"></script>
    <link href="/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection