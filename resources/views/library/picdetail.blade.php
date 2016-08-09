@extends('layout2')
@section('css')
<link href="/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css" >
@endsection
@section('body')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 图片管理 <span class="c-gray en">&gt;</span> 图片展示 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
		<span class="l"> 
			<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
		</span> 
		<span class="r">共有数据：<strong>{{count($pictures)}}</strong> 条</span> </div>
	<div class="portfolio-content">
		<ul class="cl portfolio-area">
			<form method="post" action='{{ URL::route("manage.library.picdelete") }}' id="delete">
				@foreach($pictures as $val )
				<li class="item">
					<div class="portfoliobox">
						<input class="checkbox" name="id[]" type="checkbox" value="{{$val->id}}">
						<div class="picbox ">
							<a href="{{$val->url}}" data-lightbox="gallery" data-title="{{$val->name}}">
								<img src="{{$val->url}}"></a>
						</div>
						<div class="textbox">{{$val->group_name}} </div>
					</div>
				</li>
				@endforeach
			</form>
		</ul>
	</div>
</div>

<script type="text/javascript" src="/lib/lightbox2/2.8.1/js/lightbox-plus-jquery.min.js"></script> 
<script type="text/javascript">
$(function(){
	$.Huihover(".portfolio-area li");
});

function datadel(){
	$('#delete').submit();
}
</script>
@endsection