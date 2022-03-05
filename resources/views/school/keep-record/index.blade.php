@extends('layouts.admin')

@section('content')
<style>
.penclass {
  background: #97e5ef;
  text-align: center;
}
.chalkclass {
  background: #eae7d9;
  text-align: center;
}
.brushclass {
  background: #97e5ef;
  text-align: center;
}
</style>
<div class="container">
    <div id="toolbar">
      
      月份选择
      <select id="month-selection">
        <option value="">按周筛选</option>
        <option value="20220101-20221231" selected="selected">2022全年</option>
        <option value="20220301-20220331">202203月</option>
        <option value="20220401-20220430">202204月</option>
        <option value="20220501-20220531">202205月</option>
        <option value="20220601-20220630">202206月</option>
        <option value="20220701-20220731">202207月</option>
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
                <th colspan="3" class="penclass">钢笔字</th>
                <th colspan="3" class="chalkclass">粉笔字</th>
                <th colspan="3" class="brushclass">毛笔字</th>
                <th data-field="allScoreCount" data-sortable="true" rowspan="2">
                    总分
                </th>
                <th data-field="isFormal" data-sortable="true"  rowspan="2" data-formatter="isFormalCol" data-visible="false">
                    正式标志
                </th>
            </tr>
            <tr>
              <th data-field="penPostNum" data-sortable="true" class="penclass">打卡数</th>
              <!-- <th data-field="penStarNum" data-sortable="true" class="penclass">星数</th> -->
              <th data-field="penMarkNum" class="penclass">点赞</th>
              <th data-field="penScore" data-sortable="true" class="penclass">分数</th>
              <th data-field="chalkPostNum" data-sortable="true" class="chalkclass">打卡数</th>
              <!-- <th data-field="chalkStarNum" data-sortable="true" class="chalkclass">星数</th> -->
              <th data-field="chalkMarkNum" class="chalkclass">点赞</th>
              <th data-field="chalkScore" data-sortable="true" class="chalkclass">分数</th>
              <th data-field="brushPostNum" data-sortable="true" class="brushclass">打卡数</th>
              <!-- <th data-field="brushStarNum" data-sortable="true" class="brushclass">星数</th> -->
              <th data-field="brushMarkNum" class="brushclass">点赞</th>
              <th data-field="brushScore" data-sortable="true" class="brushclass">分数</th>
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