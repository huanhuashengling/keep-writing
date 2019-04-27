@extends('layouts.admin')

@section('content')
<div class="container">
  <div class="panel panel-success col-md-6">
    <div class="panel-heading">评价标准列表</div>
    <div class="panel-body">
      <div id="toolbar1">
          <button id="rule-btn" class="btn btn-success">新增评价标准</button>
      </div>
      <table id="writing-rule-list" class="table table-condensed table-responsive">
          <thead>
              <tr>
                <th data-field="" checkbox="true">
                    
                </th>
                <th data-field="">
                    序号
                </th> 
                <th data-field="name">
                    打卡类型
                </th>
                <th data-field="rule_desc">
                    标准
                </th>
                <th data-field="weight_ratio" data-formatter="weightRatioCol">
                    权值
                </th>
                <th data-field="teachersId" data-formatter="writingRuleActionCol" data-events="writingRuleActionEvents">
                    操作
                </th> 
              </tr>
          </thead>
      </table>
    </div>
  </div>

  <div class="panel panel-success col-md-6">
    <div class="panel-heading" id="detail-title">细则列表</div>
    <div class="panel-body">
      <div id="toolbar2">
          <button id="detail-btn" class="btn btn-success hidden">新增评价细则</button>
      </div>
      <table id="writing-detail-list" class="table table-condensed table-responsive">
          <thead>
              <tr>
                <th data-field="" checkbox="true">
                    
                </th>
                <th data-field="">
                    序号
                </th> 
                <th data-field="detail_desc">
                    细则
                </th>
                <th data-field="score">
                    分值
                </th>
                <th data-field="teachersId" data-formatter="writingDetailActionCol" data-events="writingDetailActionEvents">
                    操作
                </th> 
              </tr>
          </thead>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade"  id="add-writing-rule-modal"  tabindex="-1" role="dialog" aria-labelledby="ruleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="rule-title">增加评价标准</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-4 control-label">选择对应打卡类型</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="writing-types-selection">
                      @<?php foreach ($writingTypes as $key => $writingType): ?>
                        <option value="{{$writingType->id}}">{{$writingType->name}}</option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">评价标准</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="ruleDesc" id="rule-desc" required=""></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">权值</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="weightRatio" id="weight-ratio" required="" value="25"></div>
                </div>

              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-success" id="add-rule-btn">增加</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-writing-detail-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <input type="hidden" name="" id="selected-writing-rules-id">
                <input type="hidden" name="" id="selected-writing-details-id">
                <h4 class="modal-title" id="detail-title">增加评价细则</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">

                <div class="form-group">
                  <label class="col-sm-4 control-label">评价细则</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="detailDesc" id="detail-desc" required=""></div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">分值</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="detailScore" id="detail-score" required="" placeholder="占总分5分中的多少"></div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-success" id="add-detail-btn">增加</button>
            </div>      
    </div>
  </div>
</div> 

@endsection

@section('scripts')
    <script src="/js/school/writing-rule.js?v={{rand()}}"></script>
@endsection
