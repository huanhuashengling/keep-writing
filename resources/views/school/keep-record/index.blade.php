@extends('layouts.admin')

@section('content')
<div class="container">
    <div id="toolbar">
      
      月份选择
      <select id="month-selection">
        <option value="">按周筛选</option>
        <option value="20190101-20191231">2019全年</option>
        <option value="20190901-20190930" selected="selected">201909月</option>
        <option value="20191001-20191031">201910月</option>
        <option value="20191101-20191130">201911月</option>
        <option value="20191201-20191231">201912月</option>
      </select>
      本学期周次
      <select id="week-selection">
        <option value="">按月筛选</option>
        <?php foreach ($weekDate as $key => $week): ?>
          <option value="{{$week['weekStart']}}-{{$week['weekEnd']}}">第{{$week['weekNum']}}周</option>
        <?php endforeach ?>
      </select>
    </div>
    <table id="keep-report" class="table table-condensed table-responsive">
        <thead>
            <tr>
                <th data-field="" checkbox="true" rowspan="1">

                </th>
                <th data-field="" rowspan="1">
                    序号
                </th>
                <th data-field="username" data-sortable="true" rowspan="1">
                    教师姓名
                </th>
                <th data-field="ageSection" data-sortable="true" rowspan="1">
                    年龄
                </th>
                <th colspan="1">钢笔字打卡数</th>
                <th colspan="1">粉笔字打卡数</th>
                <th colspan="1">毛笔字打卡数</th>
                <th data-field="allScoreCount" data-sortable="true" rowspan="1">
                    总分
                </th>
                <th data-field="isFormal" data-sortable="true"  rowspan="1" data-formatter="isFormalCol">
                    正式标志
                </th>
            </tr>
            <!-- <tr> -->
              <!-- <th data-field="penPostNum" data-sortable="true">打卡数</th> -->
              <!-- <th data-field="penStarNum" data-sortable="true">星数</th> -->
              <!-- <th data-field="penMarkNum">点赞</th> -->
              <!-- <th data-field="penScore" data-sortable="true">分数</th> -->
              <!-- <th data-field="chalkPostNum" data-sortable="true">打卡数</th> -->
              <!-- <th data-field="chalkStarNum" data-sortable="true">星数</th> -->
              <!-- <th data-field="chalkMarkNum">点赞</th> -->
              <!-- <th data-field="chalkScore" data-sortable="true">分数</th> -->
              <!-- <th data-field="brushPostNum" data-sortable="true">打卡数</th> -->
              <!-- <th data-field="brushStarNum" data-sortable="true">星数</th> -->
              <!-- <th data-field="brushMarkNum">点赞</th> -->
              <!-- <th data-field="brushScore" data-sortable="true">分数</th> -->
            <!-- </tr> -->
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