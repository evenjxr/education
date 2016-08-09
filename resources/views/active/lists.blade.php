@extends('layout2')
@section('body')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 活动管理 <span class="c-gray en">&gt;</span> 活动列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="text-c"> 
		<form method="get" action="{{ URL::route('manage.active.lists') }}">
			日期范围：
			<input type="text" name="start" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" id="logmin" class="input-text Wdate" style="width:120px;">
			-
			<input type="text" name="end" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d'})" id="logmax" class="input-text Wdate" style="width:120px;">
			<input type="text" name="keyword" id="" placeholder=" 活动名称" style="width:250px" class="input-text">
			<button id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜活动</button>
		</form>	
	</div>
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a class="btn btn-primary radius" data-title="添加活动" _href="{{ URL::route('manage.news.add') }}" onclick=article_add('添加资讯','{{ URL::route("manage.active.add") }}') href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加活动</a></span> <span class="r">共有数据：<strong>{{count($lists)}}</strong> 条</span> </div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="25">序号</th>
					<th width="80">活动名称</th>
					<th width="80">活动类型</th>
					<th width="80">排序值</th>
					<th width="200">审核标准</th>
					<th width="60">发布状态</th>
					<th width="120">操作</th>
				</tr>
			</thead>
			<tbody>
				@foreach($lists as $key => $val)
				<tr class="text-c">
					<td>{{$key+1}}</td>
					<td class="text-l"><u style="cursor:pointer" class="text-primary" onClick="article_edit('查看','{{ URL::route('manage.active.detail',['id'=>$val->id]) }}','10002')" title="查看">{{$val->name}}</u></td>
					<td>{{ $channel[$val->channel] }}</td>
					<td>{{$val->sort}}</td>
					<td>{{ $val->standard ? $standard[$val->standard] : '没用' }}</td>
					<td class="td-status">
						@if($val->status==0)
						<span class="label label-danger radius">未通过</span>
						@elseif($val->status==1)
						<span class="label label-success radius">草稿</span>
						@elseif($val->status==2)
						<span class="label label-success radius">已发布</span>
						@elseif($val->status==3)
						<span class="label label-info radius">已下架</span>
						@endif
					</td>

					<td class="f-14 td-manage">
						@if(in_array('Auth',Session::get('admin.moudel_name')))
							@if($val->status==0)
							<a class="c-primary" onClick="article_shenhe(this,{{$val->id}})" href="javascript:;" title="重新审核">重新审核</a>
							@elseif($val->status==1)
							<a style="text-decoration:none" onClick="article_shenhe(this,{{$val->id}})" href="javascript:;" title="审核">审核</a> 
							@elseif($val->status==2)
							<a style="text-decoration:none" onClick="article_stop(this,{{$val->id}})" href="javascript:;" title="下架"><i class="Hui-iconfont">&#xe6de;</i></a>
							@elseif($val->status==3)
							<a style="text-decoration:none" onClick="article_start(this,{{$val->id}})" href="javascript:;" title="发布"><i class="Hui-iconfont">&#xe603;</i></a>
							@endif
						@endif
						<a style="text-decoration:none" class="ml-5" onClick="article_edit('活动编辑','{{ URL::route('manage.active.detail',['id'=>$val->id]) }}','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> 
						@if(in_array($val->status,[0,1]))
						<a style="text-decoration:none" class="ml-5" onClick="article_del(this,{{$val->id}})" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
						@endif
						@if(in_array($val->channel,['other','camp','show']))
						<a style="text-decoration:none" class="ml-5" onClick="article_edit('查看喜欢','{{ URL::route('manage.common.collection',['id'=>$val->id,'moudel'=>$val->channel]) }}','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe648;</i></a>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/h-ui/js/auth.js"></script> 

<script type="text/javascript">
$('.table-sort').dataTable({
	"aaSorting": [[ 1, "desc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
	  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  {"orderable":false,"aTargets":[0,5]}// 不参与排序的列
	]
});


function shiftStatus(id,status){
	$.ajax({
     	type: "GET",
     	url: "{{ URL::route('manage.active.auth') }}",
     	data: {
     			id: id, 
     			status: status
     		},
     	dataType: "json",
     	success: function(data){
        }
    });
    return true;
}

function doDelete(ids){
	$.ajax({
		type: "POST",
		url: "{{ URL::route('manage.active.delete') }}",
		data: {
			ids: ids
		},
		dataType: "json",
		success: function(data){
		}
	});
	return true;
}


</script> 
@endsection