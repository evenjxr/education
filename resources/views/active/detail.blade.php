@extends('layout2')
@section('css')
<link rel="stylesheet" type="text/css" href="/lib/webuploader/0.1.5/webuploader.css" />
@endsection
@section('body')
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-active-update" method="post" action="{{ URL::route('manage.active.update') }}">
		<input name="id" value="{{$active->id}}" type="hidden">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>活动名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$active->name}}" placeholder="" id="" name="name">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">活动封面：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<div class="uploader-thum-container">
					<div id="fileList" class="uploader-list">
						<input type="hidden" name="thumbnail" id="thumbnail" value="{{$active->thumbnail}}">
						@if ($active->thumbnail)
							<img src="{{$active->thumbnail}}"  id="img" style="width: 400px;height: 225px;margin: 10px 0;">
						@else
							<img src=""  id="img">
						@endif
					</div>
					<button class="btn btn-success" onClick="add_pic('素材库','{{ URL::route('manage.library.picsearch')}}','900','500')" type="button"><i class="Hui-iconfont">&#xe665;</i> 图片库</button>
				</div>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>活动类型：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select name="channel" class="select">
					@foreach($channel as $k => $v)
						<option value="{{$k}}" @if($k==$active->channel) selected='selected' @endif  >{{$v}}</option>
					@endforeach
				</select>
				</span> </div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">活动公告：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" name="announcement" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)">{{$active->announcement}}</textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
			</div>
		</div>

		<div class="row cl theme change">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>开始时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text Wdate" value="{{$active->start}}" placeholder="" id="" name="start" onfocus="WdatePicker()" style="width:300px;">
			</div>
		</div>

		<div class="row cl  theme change">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>终止时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text Wdate" value="{{$active->end}}" placeholder="" id="" name="end" onfocus="WdatePicker()" style="width:300px;">
			</div>
		</div>

		<div class="row cl  theme change">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>活动地点：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" readonly value="{{$active->address}}" placeholder="发布者所属城市地点" id="" name="address">
				<input type="hidden" name="point" value="{{$active->point}}">
			</div>
		</div>

		<div class="row cl change team personal">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>审核标准：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select name="standard" class="select">
					@foreach($standard as $k => $v)
						<option value="{{$k}}" @if($k==$active->standard) selected='selected' @endif  >{{$v}}</option>
					@endforeach
				</select>
				</span>
			</div>
		</div>

		<div class="row cl personal change team other camp show">
			<label class="form-label col-xs-4 col-sm-2">排序值：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$active->sort}}" placeholder="" id="" name="sort">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">活动内容：</label>
			<div class="formControls col-xs-8 col-sm-9"> 
				<script id="editor" type="text/plain" name='content' style="width:100%;height:400px;"></script> 
			</div>
		</div>
		
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="active_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交审核</button>
				<button onClick="active_show();" class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 预览</button>
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

	UE.getEditor('editor',{
		initialContent:'<?php echo $active->content; ?>'
	});

	var channel = $('select[name="channel"]').val();
	$('.change').css('display','none');
	$('.'+channel).css('display','block');

	$('select[name="channel"]').change(function(){
		$('.change').css('display','none');
		var channel = $(this).val();
		$('.'+channel).css('display','block');
	});
});
	function add_pic(title,url,w,h){
		layer_show(title,url,w,h);
	}

	$('Input[name="address"]').click(function () {
		layer_show('地点查询','/lib/map/select.html',700,600);
	});
	function active_submit() {
		$("#form-admin-active-update").validate({
			rules:{
				name:{
					required:true
				},
				thumbnail:{
					required:true
				}
			},
			messages:{
				name:"赛程标题不得为空",
				thumbnail:"缩略图不得为空"
			},
			submitHandler:function(form){
				form.submit();
			}
		});
	}
function active_show(){
	var content = UE.getEditor('editor').getContent();
	layer.open({
		type: 1,
		skin: 'layui-layer-rim',
		area: ['400px', '600px'],
		title: '活动预览',
		content: content
	})
}
</script>
@endsection