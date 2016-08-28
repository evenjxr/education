@extends('layout2')
@section('body')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 赛程管理 <span class="c-gray en">&gt;</span> 赛程列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="text-c">
		<form method="get" action="{{ URL::route('manage.schedule.lists') }}">
			<input type="text" name="keyword" id="" placeholder="赛程" style="width:250px" class="input-text">
			<button id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜赛程</button>
		</form>	
	</div>
	<div class="cl pd-5 bg-1 bk-gray mt-20">
		<span class="r">共有数据：<strong>{{count($lists)}}</strong> 条</span> </div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="75">标题</th>
					<th width="180">活动</th>
					<th width="60">报名人数</th>
					<th width="120">操作</th>
				</tr>
			</thead>
			<tbody>
				
				@foreach($lists as $val)
				<tr class="text-c">
					<td class="text-l"><u style="cursor:pointer" class="text-primary" onClick="article_edit('查看','{{ URL::route('manage.schedule.detail',['id'=>$val->id]) }}','{{$val->id}}')" title="查看">{{$val->title}}</u></td>
					<td>
						@if($val->active)
							@foreach($val->active as $k=>$v)
							{{$v}} |
							@endforeach
						@endif
					</td>
					<td>{{$val->sign_num}}</td>
					<td class="f-14 td-manage">
						@if($val->channel=='personal')
							<a style="text-decoration:none" class="ml-5" onClick="article_edit('成绩编辑','{{ URL::route('invite',['id'=>$val->id]) }}','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
						@else
							<a style="text-decoration:none" class="ml-5" onClick="article_edit('成绩编辑','{{ URL::route('invite',['id'=>$val->id]) }}','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
						@endif
						<a style="text-decoration:none" class="ml-5" onClick="article_del(this,'{{$val->id}}')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
	  {"orderable":false,"aTargets":[ 3 ]}// 不参与排序的列
	]
});

function shiftStatus(id,status){
	$.ajax({
     	type: "GET",
     	url: "{{ URL::route('manage.schedule.auth') }}",
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
		url: "{{ URL::route('manage.schedule.delete') }}",
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