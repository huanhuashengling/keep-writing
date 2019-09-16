@extends('layouts.admin')

@section('content')
<div class="container">
    <div id="toolbar">
    </div>
    <table id="keep-report" class="table table-condensed table-responsive">
        <thead>
            <tr>
                <th data-field="" checkbox="true" rowspan="2">

                </th>
                <th data-field="" rowspan="2">
                    序号
                </th>
                <th data-field="username" data-sortable="true" rowspan="2">
                    教师姓名
                </th>
                <th data-field="ageSection" data-sortable="true" rowspan="2">
                    年龄
                </th>
                <th colspan="4">钢笔字</th>
                <th colspan="4">粉笔字</th>
                <th colspan="4">毛笔字</th>
                <th data-field="allScoreCount" data-sortable="true" rowspan="2">
                    总分
                </th>
                <th data-field="isFormal" data-sortable="true"  rowspan="2" data-formatter="isFormalCol">
                    正式标志
                </th>
            </tr>
            <tr>
              <th data-field="penPostNum" data-sortable="true">打卡数</th>
              <th data-field="penStarNum" data-sortable="true">星数</th>
              <th data-field="penMarkNum">点赞</th>
              <th data-field="penScore" data-sortable="true">分数</th>
              <th data-field="chalkPostNum" data-sortable="true">打卡数</th>
              <th data-field="chalkStarNum" data-sortable="true">星数</th>
              <th data-field="chalkMarkNum">点赞</th>
              <th data-field="chalkScore" data-sortable="true">分数</th>
              <th data-field="brushPostNum" data-sortable="true">打卡数</th>
              <th data-field="brushStarNum" data-sortable="true">星数</th>
              <th data-field="brushMarkNum">点赞</th>
              <th data-field="brushScore" data-sortable="true">分数</th>
            </tr>
        </thead>
    </table>
    
</div>
@endsection

@section('scripts')
    <script src="/js/school/keep-record.js?v={{rand()}}"></script>

    <script src="/js/FileSaver.js"></script>
    <script src="/js/bootstrap-table-export.js"></script>
    <script src="/js/tableexport.js"></script>
@endsection