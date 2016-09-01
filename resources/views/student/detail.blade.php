@extends('layout2')
@section('body')
	<body>
	<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add" method="post" action="{{ URL::route('manage.student.update') }}">
			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>真实姓名：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="text" class="input-text" value="{{$student->truename}}" placeholder="" name="truename">
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2">登录账号：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="text" class="input-text" value="{{$student->username}}" placeholder="" name="username">
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>性别：</label>
				<div class="formControls col-xs-8 col-sm-9 skin-minimal">
					<div class="radio-box">
						<input name="sex" type="radio" id="sex-1" value="1" @if($student->sex==1) checked @endif>
						<label for="sex-1">男</label>
					</div>
					<div class="radio-box">
						<input type="radio" id="sex-2" value="2" name="sex" @if($student->sex==2) checked @endif>
						<label for="sex-2">女</label>
					</div>
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>手机：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="text" class="input-text" value="{{$student->mobile}}" placeholder="" id="mobile" name="mobile">
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2">年龄：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="text" class="input-text" value="{{$student->age}}" placeholder="" name="age">
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-ms-2 col-sm-2">选择省份：</label>
				<div class="formControls col-md-3 col-sm-3"> <span class="select-box" style="width:150px;">
				<select class="select province"  size="1">
					<option >请选择省份</option>
				</select>
				</span> </div>
						<label class="form-label col-xs-4 col-sm-3">选择城市：</label>
						<div class="formControls col-md-2 col-sm-3"> <span class="select-box" style="width:150px;">
				<select class="select city" name="address_id" size="1">
					<option value="0">请选择城市</option>
				</select>
				</span> </div>
			</div>

			<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">年级：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
				<select class="select" name="grade" size="1">
					<option value="0">请选择年级</option>
					@foreach($grade as $key=>$val)
						<option value="{{$key}}" @if($student->grade==$key)  selected @endif >{{$val}}>{{$val}}</option>
					@endforeach
				</select>
				</span> 
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">自我描述：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" name="introduction" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)">{{$student->introduction}}</textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
			</div>
		</div>
			<input name="id" value="{{$student->id}}" type="hidden">
			<div class="row cl">
				<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
					<input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
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
			var address;
			$.ajax({
				type: "GET",
				url: "{{URL::route('manage.address.lists') }}",
				data: {},
				dataType: "json",
				success: function(data){
					address = data.data;
					getProvince(address);
					getcity(address);
				}
			});

			function getProvince(address) {
				var province = new Array();
				var tempProvice = '';
				$.each(address,function (name,value) {
					if(value.province != tempProvice){
						province.push(value.province);
						tempProvice = value.province;
					}
				});
				var html = '';
				$.each(province,function (index,value) {
					if (value == "{{$address->province}}" ) {
						html += "<option value='"+value+"' selected='selected'>"+value+"</option>";
					} else {
						html += "<option value='"+value+"' >"+value+"</option>";
					}
				});
				$('.province').append(html);
			}

			function getcity (address) {
				var province = $('.province').val();
				var cities = [];
				$('.city').empty();
				$.each(address,function (index,value) {
					if(value.province == province){
						cities.push(value);
					}
				});
				var html = '';
				$.each(cities,function (index,value) {
					if (value.id == '{{$address->id}}') {
						html += "<option value='"+value.id+"' selected='selected'>"+value.city+"</option>";
					} else {
						html += "<option value='"+value.id+"' >"+value.city+"</option>";
					}
				});
				$('.city').append(html);
			}

			$('.province').change(function () {
				var province = $(this).val();
				var cities = [];
				$('.city').empty();
				$.each(address,function (index,value) {
					if(value.province == province){
						cities.push(value);
					}
				});
				var html = '';
				$.each(cities,function (index,value) {
					html += "<option value='"+value.id+"' >"+value.city+"</option>";
				});
				$('.city').append(html);
			});
		</script>
@endsection