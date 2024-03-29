@extends('layouts.admin')

@section('content')
<div class="container">
<div class="panel panel-success col-md-6">
  <div class="panel-heading">导入教师账户</div>
  <div class="panel-body">
    <form role="form" method='POST' files=true>
      <input type="file" class="form-control" name="xls" id="import-teacher-account" class="file-loading">
    </form>
  </div>
</div>
<div class="panel panel-success col-md-6">
  <div class="panel-body">
    <img src="/images/kwteacherimport.png">
  </div>
</div>
<div class="panel panel-success col-md-12">
  <div class="panel-heading">管理教师账户列表</div>
  <div class="panel-body">
    <div id="toolbar">
        <button id="lock-btn" class="btn btn-danger">锁定</button>
        <button id="active-btn" class="btn btn-danger">激活</button>
        <button id="reset-pass-btn" class="btn btn-success">重置密码</button>
        <button id="add-new-btn" class="btn btn-success" value="{{ $schoolsId }}">新增教师</button>
    </div>
    <table id="teacher-list" class="table table-condensed table-responsive">
        <thead>
            <tr>
              <th data-field="" checkbox="true">
                  
              </th>
              <th data-field="">
                  序号
              </th> 
              <th data-field="username">
                  姓名
              </th>
              <th data-field="sex">
                  性别
              </th>
              <th data-field="subjects_id">
                  学科
              </th>
              <th data-field="phone_number">
                  电话号码
              </th>
              <th data-field="users_id" data-formatter="resetCol" data-events="resetActionEvents">
                  重置密码
              </th>
              <th data-field="teachersId" data-formatter="teacherAccountActionCol" data-events="teacherAccountActionEvents">
                  操作
              </th> 
            </tr>
        </thead>
    </table>
  </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-new-teacher-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">增加教师</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-2 control-label">教师姓名</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="teacherName" id="teacher-name" required=""></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">性别</label>
                  <div class="col-sm-8">
                    <select class="form-control" name="sex" id="sex" required>
                      <option value="">请选择性别</option>
                      <option value="1">男</option>
                      <option value="2">女</option>
                    </select></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">出生日期</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="birthDate" id="birth-date" required="" placeholder="例:19910826"></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">手机号码</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="phoneNumber" id="phone-number" required=""></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">任教学科</label>
                  <div class="col-sm-8">
                    <select class="form-control" name="subjectsId" id="subjectsId" required>
                      <option value="">请选择学科</option>
                      @foreach($subjects as $subject)
                        <option value="{{$subject->id}}">{{$subject->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">在编</label>
                  <div class="col-sm-8">
                    <select class="form-control" name="isFormal" id="isFormal" required>
                      <option value="">请选择是否在编</option>
                      <option value="1">是</option>
                      <option value="2">否</option>
                    </select></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">邮箱地址</label>
                  <div class="col-sm-8"><input type="text" class="form-control" name="email" id="email" required></div>
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
    <script src="/js/school/teacher-account.js?v={{rand()}}"></script>
@endsection
