@extends('layout2')
@section('body')
	<body>
	<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add" method="post" action="{{ URL::route('manage.equipment.update') }}">
			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>预约{{$equipment->member_type}}姓名：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="text" class="input-text" value="{{$equipment->truename}}" placeholder="" name="truename" readonly>
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>联系人姓名：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="text" class="input-text" value="{{$equipment->link_name}}" placeholder="" id="mobile" name="link_name">
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>联系人电话：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="text" class="input-text" value="{{$equipment->mobile}}" placeholder="" id="mobile" name="mobile">
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>预约时间：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="text" class="input-text" value="{{$equipment->time}}" placeholder="" id="mobile" name="mobile">
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>预约类型：</label>
				<div class="formControls col-xs-8 col-sm-9 skin-minimal">
					<div class="radio-box">
						<input name="recommend_type" type="radio" id="sex-1" value="1" @if($equipment->recommend_type==1) checked @endif>
						<label for="sex-1">平台推荐</label>
					</div>
					<div class="radio-box">
						<input type="radio" id="sex-2" value="2" name="recommend_type" @if($equipment->recommend_type==2) checked @endif>
						<label for="sex-2">指定老师</label>
					</div>
				</div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-3 col-sm-2">选择省份：</label>
				<div class="formControls col-md-3 col-sm-3"> <span class="select-box" style="width:150px;">
				<select class="select province"  size="1">
					<option >请选择省份</option>
				</select>
				</span> </div>
				<label class="form-label col-xs-3 col-sm-2">选择城市：</label>
				<div class="formControls col-md-2 col-sm-3"> <span class="select-box" style="width:150px;">
				<select class="select city" name="address_id" size="1">
					<option value="0">请选择城市</option>
				</select>
				</span> </div>
			</div>

			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>详细地址：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<textarea cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" name="address_detail" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)">{{$equipment->address_detail}}</textarea>
					<p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
				</div>
			</div>


			<div class="row cl">
				<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
					<button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交审核</button>
					<button onClick="layer_close();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
				</div>
			</div>

		</div>
		<input name="id" value="{{$equipment->id}}" type="hidden">
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
							isPhone:true
						},
						email:{
							required:true,
							email:true
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

			});




		</script>
@endsection