@extends('layouts.admin')

@section('content')
<div class="container">
    <div id="toolbar">
    </div>
    <table id="keep-report" class="table table-condensed table-responsive">
        <thead>
            <tr>
                <th data-field="" checkbox="true">

                </th>
                <th data-field="">
                    序号
                </th>
                <th data-field="username" data-sortable="true">
                    教师姓名
                </th>
                <th data-field="postedNum" data-sortable="true">
                    打卡次数
                </th>
                <th data-field="rateYouJiaNum" data-sortable="true">
                    优
                </th>
                <th data-field="rateYouNum" data-sortable="true">
                    良
                </th>
                <th data-field="markNum" data-sortable="true">
                    点赞
                </th>
                <th data-field="commentNum" data-sortable="true">
                    评论
                </th>
                <th data-field="scoreCount" data-sortable="true">
                    分数合计
                </th>
            </tr>
        </thead>
    </table>
    
</div>
@endsection

@section('scripts')
    <script src="/js/school/keep-record.js"></script>

    <script src="/js/FileSaver.js"></script>
    <script src="/js/bootstrap-table-export.js"></script>
    <script src="/js/tableexport.js"></script>
@endsection