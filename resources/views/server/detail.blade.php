@extends('layout2')
@section('body')
	<body>
	<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add" method="post" action="{{ URL::route('manage.server.update') }}">
			<div class="row cl">
				<label class="form-label col-xs-2 col-sm-2">下单电话：</label>
				<div class="formControls col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{$server->mobile}}" placeholder="" name="mobile">
				</div>
				<label class="form-label col-xs-2 col-sm-2">作业辅导：</label>
				<div class="formControls col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{$server->homework_server}}" placeholder="" name="homework_server">
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-2 col-sm-2">预习/复习：</label>
				<div class="formControls col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{$server->prepare_server}}" placeholder="" name="prepare_server">
				</div>
				<label class="form-label col-xs-2 col-sm-2">补习：</label>
				<div class="formControls col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{$server->extra_server}}" placeholder="" name="extra_server">
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-2 col-sm-2">学生姓名：</label>
				<div class="formControls col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{$server->student_name}}">
				</div>
				<label class="form-label col-xs-2 col-sm-2">预约老师：</label>
				<div class="col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{$server->teacher->truename}}">
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-2 col-sm-2">预约老师电话：</label>
				<div class="formControls col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{$server->teacher->mobile}}">
				</div>
				<label class="form-label col-xs-2 col-sm-2">推荐人：</label>
				<div class="col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{ isset($server->referrer->type) ? $server->referrer->type :''}} -- {{ isset($server->referrer->truename) ? $server->referrer->truename : ''}}">
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-2 col-sm-2">推荐人电话：</label>
				<div class="formControls col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{ isset($server->referrer->mobile) ? $server->referrer->mobile : ''}}">
				</div>
				<label class="form-label col-xs-2 col-sm-2">外勤人员：</label>
				<div class="col-xs-3 col-sm-3">
					<input type="text" class="input-text" value="{{$server->manage_name}}">
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-2 col-sm-2">设备台数：</label>
				<div class="formControls col-xs-3 col-sm-3">
					<input type="text" class="input-text" name="equipment" value="{{$server->equipment}}">
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-2 col-sm-2">价格：</label>
				<div class="formControls col-xs-7 col-sm-7" style="padding: 10px 20px; border: 1px solid #ccc;background: #00b7ee;margin-left:15px;color: #ff5500">
						{{ $server->equipment }} * {{$fee['equipment_fee']}} +
						{{ $server->homework_server }} * {{$fee['homework_fee']}} +
						{{ $server->prepare_server }} * {{$fee['prepare_fee']}} +
						{{ $server->extra_server }} * {{$server->teacher->extra_server_fee}} =
						{{ $server->total_fee }}  元
						</br>
						支付老师 {{ $server->total_fee*0.99 }}  元
						</br>
						推荐费用 {{$server->total_fee*0.01}}  元
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-2 col-sm-2">评价：</label>
				<div class="formControls col-xs-8 col-sm-8">
					<textarea cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" name="comment" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)">{{$server->comment}}</textarea>
					<p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
				</div>
			</div>
			<input name="id" value="{{$server->id}}" type="hidden">
			<div class="row cl">
				<div class="col-xs-7 col-sm-9 col-xs-offset-4 col-sm-offset-3">
					<button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
					<button onClick="layer_close();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
				</div>
			</div>
		</form>
	</article>
	@endsection
	@section('js')
		<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/validate-methods.js"></script>
		<script type="text/javascript" src="http://lib.h-ui.net/jquery.validation/1.14.0/messages_zh.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$('.skin-minimal input').iCheck({
					checkboxClass: 'icheckbox-blue',
					radioClass: 'iradio-blue',
					increaseArea: '20%'
				});

				$("#form-admin-add").validate({
					rules:{
						name:{
							required:true,
							minlength:2,
							maxlength:8
						},
						mobile:{
							required:true,
							isPhone:true,
						},
						email:{
							required:true,
							email:true,
						}
						idcard:{
							required:true,
						}
					},
					onkeyup:false,
					focusCleanup:true,
					success:"valid",
					submitHandler:function(form){
						$(form).ajaxSubmit();
						var index = parent.layer.getFrameIndex(window.name);
						parent.$('.btn-refresh').click();
						parent.layer.close(index);
					}
				});
			});
		</script>
@endsection