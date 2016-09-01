@extends('layout2')
@section('body')
<body>
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add" method="post" action="{{ URL::route('manage.admin.store') }}">
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>管理员姓名：</label>
		<div class="formControls col-xs-8 col-sm-9">
			<input type="text" class="input-text" value="" placeholder="" id="adminName" name="truename">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>初始密码：</label>
		<div class="formControls col-xs-8 col-sm-9">
			<input type="password" class="input-text" autocomplete="off" value="" placeholder="密码" id="password" name="password">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>确认密码：</label>
		<div class="formControls col-xs-8 col-sm-9">
			<input type="password" class="input-text" autocomplete="off"  placeholder="确认新密码" id="password2" name="password2">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>手机：</label>
		<div class="formControls col-xs-8 col-sm-9">
			<input type="text" class="input-text" value="" placeholder="" id="mobile" name="mobile">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3">选择省份：</label>
		<div class="formControls col-md-2 col-sm-3"> <span class="select-box" style="width:150px;">
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
			account:{
				required:true,
				minlength:4,
				maxlength:16
			},
			password:{
				required:true,
			},
			password2:{
				required:true,
				equalTo: "#password"
			},
			mobile:{
				required:true,
				isPhone:true,
			},
			email:{
				required:true,
				email:true,
			},
			adminRole:{
				required:true,
			},
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

	var address;
	$.ajax({
		type: "GET",
		url: "{{URL::route('manage.address.lists') }}",
		data: {},
		dataType: "json",
		success: function(data){
			address = data.data;
			console.log(address);
			getProvince(address);
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
			html += "<option value='"+value+"' >"+value+"</option>";
		});
		$('.province').append(html);
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
});
</script> 
@endsection