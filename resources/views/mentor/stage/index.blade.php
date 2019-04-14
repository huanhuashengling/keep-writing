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
<input type="hidden" id="posts-id" value="">

    <div class="modal-dialog modal-lg" role="document" style="width: 1650px; margin: auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-6">
                    <input type="hidden" name="" id="selected-posts-id" />
                    <input type="hidden" name="" id="selected-teachers-id" />
            
                    <img src="" id='post-show' class="img-responsive img-thumbnail center-block" />
                </div>
                <div class="col-md-6" style="padding-top: 400px;">
                    
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-2 control-label">优点：</label>

                            <?php foreach ($writingDetails as $key => $writingDetail): ?>
                                <button class="btn btn-primary btn-sm good-detail-btn" id="good-detail-{{$writingDetail->id}}" value="{{$writingDetail->id}}">{{$writingDetail->detail_desc}}</button>
                            <?php endforeach ?>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">不足：</label>

                            <?php foreach ($writingDetails as $key => $writingDetail): ?>
                                <button class="btn btn-primary btn-sm detail-btn" id="bad-detail-{{$writingDetail->id}}" value="{{$writingDetail->id}}">{{$writingDetail->detail_desc}}</button>
                            <?php endforeach ?>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10">
                                <input type="" name="" class="form-control" id="other-comment-content">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="submit-other-comment-content">提交其他评价</button>
                            </btn>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">整体评价：</label>
                            <input id="input-1" name="input-1" class="rating rating-loading col-md-10" data-min="0" data-max="5" data-step="1">
                        </div>
                    </form>
                    <div class="alert alert-success hidden" id="success-alert">
                        <h4>提交评价成功！</h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-success" id="prev-btn"><<往前</button> -->
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <!-- <button type="button" class="btn btn-success" id="next-btn" >往后>></button> -->
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