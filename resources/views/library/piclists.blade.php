@extends('layout2')
@section('body')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 图片管理 <span class="c-gray en">&gt;</span> 图片列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="text-c"> 日期范围：
		<input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" id="logmin" class="input-text Wdate" style="width:120px;">
		-
		<input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d'})" id="logmax" class="input-text Wdate" style="width:120px;">
		<input type="text" name="" id="" placeholder=" 图片名称" style="width:250px" class="input-text">
		<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜图片</button>
	</div>
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
		<span class="l">
			<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> 
			<a class="btn btn-primary radius" onclick="article_add('添加图片','{{ URL::route("manage.library.picadd") }}')" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加图片</a>
		</span> 
		<span class="r">共有数据：<strong>{{count($piclists)}}</strong> 条</span> </div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="80">序号</th>
					<th width="100">分类</th>
					<th width="100">组名</th>
					<th width="150">更新时间</th>
					<th width="100">操作</th>
				</tr>
			</thead>
			<tbody>
				@foreach($piclists as $key=>$val)
				<tr class="text-c">
					<td>{{$key+1}}</td>
					<td>
						<a href="javascript:;" onClick="article_edit('图库编辑','{{ URL::route('manage.library.picdetail',['group_name'=>$val->group_name]) }}')">{{$val->moudel_name}}</a>
					</td>
					<td>
						<a href="javascript:;" onClick="article_edit('图库编辑','{{ URL::route('manage.library.picdetail',['group_name'=>$val->group_name]) }}')">{{$val->group_name}}</a>
					</td>
					<td>{{$val->created_at}}</td>
					<td class="td-manage">
						<a style="text-decoration:none" class="ml-5" onClick="article_edit('图库编辑','{{ URL::route('manage.library.piceditgroup',['group_name'=>$val->group_name]) }}','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe61f;</i></a>
						<a style="text-decoration:none" class="ml-5" onClick="article_del(this,'{{$val->group_name}}')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="/static/h-ui/js/auth.js"></script> 
<script type="text/javascript">

$('.table-sort').dataTable({
	"aaSorting": [[ 1, "desc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
	  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  {"orderable":false,"aTargets":[0,8]}// 制定列不参与排序
	]
});


function doDelete(group_name){
	alert(group_name);
	$.ajax({
     	type: "POST",
     	url: "{{ URL::route('manage.library.picdeletegroup') }}",
     	data: {
     			group_name: group_name
     		},
     	dataType: "json",
     	success: function(data){
        }
    });
    return true;
}
</script>
@endsection