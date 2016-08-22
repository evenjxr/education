@extends('layout2')
@section('body')
<body>
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add" method="post" action="{{ URL::route('manage.teacher.update') }}">
		<input type="hidden" name="id" value="{{$teacher->id}}">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">真实姓名：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$teacher->truename}}" placeholder="" name="truename">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>账号：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$teacher->username}}" placeholder="" name="username">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>性别：</label>
			<div class="formControls col-xs-8 col-sm-9 skin-minimal">
				<div class="radio-box">
					<input name="sex" type="radio" id="sex-1" value="1" @if($teacher->sex==1) checked @endif>
					<label for="sex-1">男</label>
				</div>
				<div class="radio-box">
					<input type="radio" id="sex-2" value="1" name="sex"  @if($teacher->sex==2) checked @endif>
					<label for="sex-2">女</label>
				</div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">年龄：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" value="{{$teacher->age}}" placeholder="" name="age">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>手机：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$teacher->mobile}}" placeholder="" id="mobile" name="mobile">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">学校名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$teacher->school_name}}" placeholder="北京 人大附中" name="school_name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">职称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$teacher->profession_title}}" placeholder="高级教师 特技教师" name="profession_title">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">工龄：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" value="{{$teacher->worked_year}}" placeholder="高级教师 特技教师" name="worked_year">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">推荐数：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" value="{{$teacher->hits}}" placeholder="推荐数越多排名越靠前" name="hits">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">好评星：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" value="{{$teacher->star}}" placeholder="好评" name="star">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">城市：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
				<select class="select" name="address" size="1">
					<option value="0">请选择城市</option>
					@foreach($addresses as $key=>$val)
						<option value="{{$key}}" name="address[]" @if(($key == $teacher->address))  checked @endif>{{$val}}</option>
					@endforeach
				</select>
				</span> </div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">年级：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
					@foreach($grade as $key=>$val)
					<label for="{{$key}}"> 
					   <input id='{{$key}}' name="grade" type="radio" value="{{$key}}" @if($key==$teacher->grade)  checked @endif>{{$val}}
					</label>
					@endforeach
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">可授课年级：</label>
			<div class="formControls col-xs-8 col-sm-9">
				@foreach($grades as $key=>$val)
					<label for="{{$key}}">
						<input id='{{$key}}' name="grade" type="checkbox" value="{{$key}}" @if(in_array($key,$teacher->grades))  checked @endif>{{$val}}
					</label>
				@endforeach
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">可工作时间：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
					@foreach($workTime as $key=>$val)
					<label for="{{$key}}"> 
					   <input id='{{$key}}' name="work_time[]" type="checkbox" value="{{$key}}" @if(in_array($key,$teacher->work_time))  checked @endif>{{$val}}
					</label>
					@endforeach
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">课业辅导：</label>
			<div class="formControls col-xs-8 col-sm-9">
				@foreach($schoolwork as $key=>$val)
					<label for="{{$key}}">
						<input id='{{$key}}' name="schoolwork[]" type="checkbox" value="{{$key}}"  @if(in_array($key,$teacher->schoolwork))  checked @endif>{{$val}}
					</label>
				@endforeach
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">所授科目：</label>
			<div class="formControls col-xs-8 col-sm-9">
				@foreach($subject as $key=>$val)
					<label for="{{$key}}">
						<input id='{{$key}}' name="subject" type="radio" value="{{$key}}" @if($teacher->subject == $key)  checked @endif>{{$val}}
					</label>
				@endforeach
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">可辅导科目：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
					@foreach($subjects as $key=>$val)
					<label for="{{$key}}"> 
					   <input id='{{$key}}' name="subject" type="checkbox" value="{{$key}}" @if(in_array($key,$teacher->subjects))  checked @endif>{{$val}}
					</label>
					@endforeach
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">个人履历：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" name="profile" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)">{{$teacher->profile}}</textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">自我描述：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" name="introduction" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)">{{$teacher->introduction}}</textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
			</div>
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
			username:{
				required:true,
				minlength:2,
				maxlength:20
			},
			mobile:{
				required:true,
				isPhone:true,
			},
			school_name:{
				required:true,
				minlength:2,
			},
			age:{
				required:true,
			},
			grade:{
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