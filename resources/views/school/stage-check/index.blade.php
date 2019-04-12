@extends('layouts.admin')

@section('content')
<div class="container">
  <div class="panel panel-success col-md-6">
    <div class="panel-heading">三笔一话 阶段检查列表</div>
    <div class="panel-body">
      <div id="toolbar">
          <button id="add-new-btn" class="btn btn-success" value="{{$schoolsId}}">新增阶段检查</button>
      </div>
      <table id="stage-check-list" class="table table-condensed table-responsive">
          <thead>
              <tr>
                <th data-field="" checkbox="true">
                    
                </th>
                <th data-field="">
                    序号
                </th> 
                <th data-field="check_date">
                    打卡时间
                </th>
                <th data-field="name">
                    打卡类型
                </th>
                <th data-field="teachersId" data-formatter="stageCheckActionCol" data-events="stageCheckActionEvents">
                    操作
                </th> 
              </tr>
          </thead>
      </table>
    </div>
  </div>

  <div class="panel panel-success col-md-6">
    <div class="panel-heading" id="report-title">打卡情况</div>
    <div class="panel-body">
      <div id="toolbar">
      </div>
      <table id="stage-check-report" class="table table-condensed table-responsive">
          <thead>
              <tr>
                <th data-field="" checkbox="true">
                    
                </th>
                <th data-field="">
                    序号
                </th> 
                <th data-field="username">
                    打卡教师
                </th>
                <th data-field="rate">
                    星级
                </th>
              </tr>
          </thead>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-new-stage-check-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">增加阶段打卡时间</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-4 control-label">检查时间</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="checkDate" id="check-date" required="" placeholder="日期格式如：20190405"></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 control-label">阶段检查类别</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="writing-types-selection">
                      @<?php foreach ($writingTypes as $key => $writingType): ?>
                        <option value="{{$writingType->id}}">{{$writingType->name}}</option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-success" id="confirm-add-new-btn">增加</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <link href="/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <script src="/js/fileinput.min.js"></script>
    <script src="/js/locales/zh.js"></script>
    <script src="/js/school/stage-check.js?v={{rand()}}"></script>
@endsection
