@extends('layout2')
@section('css')
<link href="/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
@endsection
@section('body')
<div class="page-container">
	<form class="form form-horizontal" method="post" action='{{ URL::route("manage.library.videostore") }}' enctype="multipart/form-data">
        <div class="row cl" id="url"></div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">视频名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="" name="name">
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">视频：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="file" class="input-text" name="video">
            </div>
        </div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
				<button onClick="layer_close();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
	</form>
</div>
@endsection
@section('js')
<script type="text/javascript" src="/lib/webuploader/0.1.5/webuploader.min.js"></script>
@endsection