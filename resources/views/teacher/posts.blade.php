@extends('layouts.teacher')

@section('content')
<div class="container">
    <div class="row">
        <div id="posts-list"></div>
    </div>

<!-- Modal -->
<div class="modal fade" id="myPostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" id="posts-id" value="">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">查看作业</h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0px; text-align: center;">
      <!-- <iframe src='https://docview.mingdao.com/op/embed.aspx?src=http://www.ccut.edu.tw/teachers/cskuan/downloads/ed01-ch01.ppt' width='800px' height='600px' frameborder='0'>This is an embedded <a target='_blank' href='http://office.com'>Microsoft Office</a> document, powered by <a target='_blank' href='http://office.com/webapps'>Office Web Apps</a>.</iframe> -->
                <img src="" id='post-show' width="330px" height="530">
        <!-- https://docview.mingdao.com/op/generate.aspx -->
        <!-- <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=http://www.ccut.edu.tw/teachers/cskuan/downloads/ed01-ch01.ppt' width='800px' height='600px' frameborder='0'> -->
        <!-- <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=http://www.lf1.cuni.cz/zfisar/psychiatry/Child%20Psychiatry.ppt' width='800px' height='600px' frameborder='0'> -->
        <!-- </iframe> -->
                    
                    <!-- <h4>该作业被评为:<b><label id="1rate-label"></label></b></h4> -->
                    <!-- <h4>点赞：<small>刘奥，刘胜翔</small></h4> -->
                <!-- <div class="form-group">
                    <h4>老师评语：</h4>
                    <textarea rows='3' id="1post-comment" class="form-control" readonly="readonly"  value=''></textarea>
                </div> -->
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary" id="post-download">文件下载</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="/js/teacher/posts.js?v={{rand()}}"></script>

@endsection