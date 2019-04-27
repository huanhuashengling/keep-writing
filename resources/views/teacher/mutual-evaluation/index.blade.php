@extends('layouts.teacher')

@section('content')
<div class="container">
    @if ("" == $post)
        <button class="btn btn-primary" id="start-again-btn">运气不好，点我刷新一次</button>
    @else
    <div class="col-md-12">
        <input type="hidden" name="" id="selected-posts-id" value="{{@$post->id}}" />
        <input type="hidden" name="" id="selected-teachers-id" />

        <!-- <img src="" id='post-show' class="img-responsive img-thumbnail center-block" /> -->
        <img class="img-responsive thumb-img center-block" value="{{ @$post['pid'] }}" src="{{ getThumbnail(@$post->storage_name, 350, 450, $schoolCode, 'background', @$post->file_ext) }}" alt="">
    </div>
        <div class="alert alert-success">
            <li>评价别人的作品可以锻炼眼力，找到差距。</li>
            <li>评价请客观公正，我们一起来提高书写水平！</li>
        </div>
    <div class="col-md-12" >
        <form class="form-horizontal">
            <div class="form-group">
                <?php foreach ($writingDetailsData as $key => $writingDetails): ?>
                    <?php echo $key . ":"; ?>
                    <?php foreach ($writingDetails as $key => $writingDetail): ?>
                     <button class="btn btn-primary btn-xs good-detail-btn" id="good-detail-{{$writingDetail['id']}}" value="{{$writingDetail['id']}}" score="{{$writingDetail['score']}}">{{$writingDetail['desc']}}</button>
                    <?php endforeach ?>
                    <br />
                <?php endforeach ?>
            </div>
            <div class="form-group">
                <input id="input-1" name="input-1" class="rating rating-loading col-md-10 col-xs-10" readonly="readonly" data-size="sm">
            </div>
            <div class="form-group">
                <div class="col-md-4 col-xs-4">
                    <input type="" name="" class="form-control" id="good-word" value="百" placeholder="一个好字">
                </div>
                <div class="col-md-5 col-xs-5">
                    <input type="" name="" class="form-control" id="bad-word" value="读" placeholder="一个薄弱字">
                </div>
                <div class="col-md-3 col-xs-3">
                    <button class="btn btn-success" id="submit-comment">提交</button>
                </btn>
            </div>
        </form>
        <!-- <div class="alert alert-success hidden" id="success-alert">
            <h4>提交评价成功！</h4>
        </div> -->
    </div>
    @endif
    
</div>

<!-- Modal -->
<div class="modal fade" id="againModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>提交评价成功！</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="again-btn">再评一幅</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div> 
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="msg-title">提交评价成功！</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div> 
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="/js/teacher/mutual-evaluation.js?v={{rand()}}"></script>
    <script src="/js/star-rating.min.js"></script>
    <script src="/js/locales/star-zh.js"></script>
    <link href="/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection