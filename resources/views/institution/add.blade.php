@extends('layout2')
@section('css')
<link rel="stylesheet" type="text/css" href="/lib/webuploader/0.1.5/webuploader.css" />
@endsection
@section('body')
<body>
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add" method="post" action="{{ URL::route('manage.institution.store') }}">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">机构名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" name="name">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>企业法人：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" name="legal_person">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>电话：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" name="mobile">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">执照：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<div class="uploader-thum-container">
					<div id="fileList" class="uploader-list">
						<input name="certificate_url" value="" type="hidden" id="thumbnail">
						<img src="" id="img">
					</div>
					<div id="filePicker">选择图片</div>
					<button id="btn-star" class="btn btn-default btn-uploadstar radius ml-10">开始上传</button>
				</div>
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">选择省份：</label>
			<div class="formControls col-md-2 col-sm-2"> <span class="select-box" style="width:150px;">
			<select class="select province"  size="1">
				<option >请选择省份</option>
			</select>
			</span> </div>
			<label class="form-label col-xs-2 col-sm-3">选择城市：</label>
			<div class="formControls col-md-2 col-sm-3"> <span class="select-box" style="width:150px;">
			<select class="select city" name="address_id" size="1">
				<option value="0">请选择城市</option>
			</select>
			</span> </div>
		</div>


		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">机构简介：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" name="introduction" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)"></textarea>
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
<script type="text/javascript" src="/lib/webuploader/0.1.5/webuploader.min.js"></script> 


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
			legal_person:{
				required:true,
				minlength:2,
				maxlength:8
			},
			password:{
				required:true,
			},
			mobile:{
				required:true,
				isPhone:true,
			},
			address_id:{
				required:true
			},
			introduction:{
				required:true
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


	$list = $("#fileList"),
			$btn = $("#btn-star"),
			state = "pending",
			uploader;

	var uploader = WebUploader.create({
		auto: true,
		swf: '/lib/webuploader/0.1.5/Uploader.swf',

		// 文件接收服务端。
		server: '{{ URL::route("manage.common.upload") }}',

		// 选择文件的按钮。可选。
		// 内部根据当前运行是创建，可能是input元素，也可能是flash.
		pick: '#filePicker',

		// 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
		resize: false,
		// 只允许选择图片文件。
		accept: {
			title: 'Images',
			extensions: 'gif,jpg,jpeg,bmp,png',
			mimeTypes: 'image/*'
		}
	});
	uploader.on( 'fileQueued', function( file ) {
		var $li = $(
						'<div id="' + file.id + '" class="item">' +
						'<div class="pic-box"><img></div>'+
						'<div class="info">' + file.name + '</div>' +
						'<p class="state">等待上传...</p>'+
						'</div>'
				),
				$img = $li.find('img');
		$list.append( $li );

		// 创建缩略图
		// 如果为非图片文件，可以不用调用此方法。
		// thumbnailWidth x thumbnailHeight 为 100 x 100
		uploader.makeThumb( file, function( error, src ) {
			if ( error ) {
				$img.replaceWith('<span>不能预览</span>');
				return;
			}
			$img.attr( 'src', src );
		}, 400, 270 );
	});
	// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {
		var $li = $( '#'+file.id ),
				$percent = $li.find('.progress-box .sr-only');

		// 避免重复创建
		if ( !$percent.length ) {
			$percent = $('<div class="progress-box"><span class="progress-bar radius"><span class="sr-only" style="width:0%"></span></span></div>').appendTo( $li ).find('.sr-only');
		}
		$li.find(".state").text("上传中");
		$percent.css( 'width', percentage * 100 + '%' );
	});

	// 文件上传成功，给item添加成功class, 用样式标记上传成功。
	uploader.on( 'uploadSuccess', function( file ,response ) {
		$('#thumbnail').val(response._raw);
		$( '#'+file.id ).addClass('upload-state-success').find(".state").text("已上传");
	});

	// 文件上传失败，显示上传出错。
	uploader.on( 'uploadError', function( file ) {
		$( '#'+file.id ).addClass('upload-state-error').find(".state").text("上传出错");
	});

	// 完成上传完了，成功或者失败，先删除进度条。
	uploader.on( 'uploadComplete', function( file ) {
		$( '#'+file.id ).find('.progress-box').fadeOut();
	});
	uploader.on('all', function (type) {
		if (type === 'startUpload') {
			state = 'uploading';
		} else if (type === 'stopUpload') {
			state = 'paused';
		} else if (type === 'uploadFinished') {
			state = 'done';
		}

		if (state === 'uploading') {
			$btn.text('暂停上传');
		} else {
			$btn.text('开始上传');
		}
	});

	$btn.on('click', function () {
		if (state === 'uploading') {
			uploader.stop();
		} else {
			uploader.upload();
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