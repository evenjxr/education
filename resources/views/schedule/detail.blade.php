@extends('layout2')
@section('css')
<link rel="stylesheet" type="text/css" href="/lib/webuploader/0.1.5/webuploader.css" />
@endsection
@section('body')
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-schedule-update" method="post" action="{{ URL::route('manage.schedule.update') }}">
		<input type="hidden" value="{{Session::get('admin.city_id')}}" name="city_id">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>赛程标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$schedules->title}}" placeholder="" id="" name="title">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>比赛类型：</label>
			<div class="formControls col-xs-8 col-sm-10">
				<span class="select-box" style="width: 300px;">
				<select name="channel" class="select">
					<option value="">---请选择赛程类型---</option>
					@foreach($channel as $key=>$value)
						<option value="{{$key}}" @if($key == $schedules->channel) selected="true" @endif>{{$value}}</option>
					@endforeach
				</select>
				</span>
			</div>
		</div>
		<input type="hidden" value="{{$schedules->id}}" name="id">
	 	<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>赛程开始时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text Wdate" value="{{$schedules->start}}" placeholder="" id="" name="start" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:300px;">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>赛程结束时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text Wdate" value="{{$schedules->end}}" placeholder="" id="" name="end" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:300px;">
			</div>
		</div>
		<div class="row cl change show personal team">
			<label class="form-label col-xs-4 col-sm-2">时间Tag：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$schedules->date_tag}}" placeholder="" id="" name="date_tag" style="width: 300px;">
			</div>
		</div>
		<div class="row cl change personal team">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>提前检录时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$schedules->check}}" placeholder="单位分钟" id="" name="check" onfocus="WdatePicker({dateFmt:'HH:mm:ss'})" style="width:300px;">
			</div>
		</div>
		<div class="row cl change personal team show">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>添加活动：</label>
			<div class="formControls col-xs-8 col-sm-9 active">
				<button id="" class="btn btn-success" onClick="add_active('活动编辑','{{ URL::route('manage.active.search')}}','900','580')" type="button"><i class="Hui-iconfont">&#xe665;</i> 活动库</button>
				<span class="c-red">(如果你赛程是团队赛 请选择团体活动 反之)</span>
				@foreach($actives as $key=>$value )
				<div class="li">
					<input name="active_id[]" type="hidden" value="{{$value->active_id}}">
					<div class="row cl">
						<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>活动{{$key+1}}：</label>
						<div class="formControls col-xs-8 col-sm-9"> 
							<input type="text" class="input-text" value="{{ $value->getActiveName()->getResults()['name'] }}" style="width: 300px;margin-right: 10px;" readonly="true">
							<button class="btn btn-danger" onClick="del_active(this)" type="button">移除</button>
						</div>
					</div>
				</div>
				@endforeach
			</div>
			
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">缩略图：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<div class="uploader-thum-container">
					<div id="fileList" class="uploader-list">
						<input type="hidden" name="thumbnail" id="thumbnail" value="{{$schedules->thumbnail}}">
						@if ($schedules->thumbnail)
							<img src="{{$schedules->thumbnail}}" id="img" style="width: 400px;height: 225px;margin: 10px 0;">
						@else
							<img src=""  id="img">
						@endif
					</div>
					<button class="btn btn-success" onClick="add_pic('素材库','{{ URL::route('manage.library.picsearch')}}','900','580')" type="button"><i class="Hui-iconfont">&#xe665;</i> 图片库</button>
				</div>
			</div>
		</div>

		<div class="row cl change personal team">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>报名人数：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$schedules->min_num}}" placeholder="最少" style="width: 200px" id="" name="min_num">
				到
				<input type="text" class="input-text" value="{{$schedules->max_num}}" placeholder="最多" style="width: 200px" id="" name="max_num">
			</div>
		</div>

		<div class="row cl change personal team">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>报名费用：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$schedules->fee}}" placeholder="" style="width: 200px" id="" name="fee">
				优惠价
				<input type="text" class="input-text" value="{{$schedules->discount}}" placeholder="" style="width: 200px" id="" name="discount">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">赛程公告：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" name="announcement" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)">{{$schedules->announcement}}</textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">赛程详情：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
				<script id="editor" type="text/plain" name='content' style="width:100%;height:400px;"></script> 
			</div>
		</div>

		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="schedule_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交审核</button>
				<button onClick="schedule_show();" class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 预览</button>
				<button onClick="removeIframe();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
	</form>
</article> 
@endsection

@section('js')
<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/lib/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/lib/ueditor/1.4.3/ueditor.config.js"></script> 
<script type="text/javascript" src="/lib/ueditor/1.4.3/ueditor.all.min.js"> </script> 
<script type="text/javascript" src="/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
$(function(){
	$('.change').hide();
	var url = $('#img').attr('src');
	if (url) {
		$('#img').css({width:'400px',height:'225px'});
	}

	UE.getEditor('editor',{
		initialContent:'<?php echo $schedules->content; ?>'
	})

	$('select[name="channel"]').change(function(){
		$('.change').hide();
		var channel = $(this).val();
		$('.'+channel).show();
	});

	var channel = "{{$schedules->channel}}";
	$('.'+channel).show();
	
})

function schedule_submit() {

	$("#form-admin-schedule-update").validate({
		rules:{
			title:{
				required:true
			},
			start:{
				required:true
			},
			end:{
				required:true
			},
			thumbnail:{
				required:true
			}
		},
		messages:{
			title:"赛程标题不得为空",
			start:"开始类型不得为空",
			end:"结束不得为空",
			thumbnail:"缩略图不得为空"
		},
		submitHandler:function(form){
			form.submit();
		}
	});
}
function add_pic(title,url,w,h){
	layer_show(title,url,w,h);
}
function add_active(title,url,w,h){
	layer_show(title,url,w,h);
}

function del_active(obj){
	layer.confirm('确认要删除吗？',function(){
		console.log($(obj).parents(".li"));
		$(obj).parents(".li").remove();
		layer.msg('已删除!');
	});
}

function schedule_show(){
	var content = UE.getEditor('editor').getContent();
	layer.open({
		type: 1,
		skin: 'layui-layer-rim',
		area: ['400px', '600px'],
		title: '赛程预览',
		content: content
	})
}


</script>
@endsection