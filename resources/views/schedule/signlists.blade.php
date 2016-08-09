@extends('layout2')
@section('body')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 赛程管理 <span class="c-gray en">&gt;</span> 赛程列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="text-c">
		{{--<form method="get" action="{{ URL::route('manage.schedule.lists') }}">--}}
		    {{--<span class="select-box inline">--}}
				{{--<select name="status" class="select">--}}
					{{--<option value="">全部</option>--}}
					{{--<option value="0">未通过</option>--}}
					{{--<option value="1">草稿</option>--}}
					{{--<option value="2">上线</option>--}}
					{{--<option value="3">下线</option>--}}
				{{--</select>--}}
			{{--</span> 日期范围：--}}
			{{--<input type="text" name="start" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" id="logmin" class="input-text Wdate" style="width:120px;">--}}
			{{-----}}
			{{--<input type="text" name="end" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d'})" id="logmax" class="input-text Wdate" style="width:120px;">--}}
			{{--<input type="text" name="keyword" id="" placeholder="赛程" style="width:250px" class="input-text">--}}
			{{--<button id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜赛程</button>--}}
		{{--</form>	--}}
	</div>
	<div class="cl pd-5 bg-1 bk-gray mt-20">
		<span class="l">
			<a class="btn btn-primary radius" data-title="报名" onclick=article_add('报名','{{ URL::route("manage.schedule.signup") }}')><i class="Hui-iconfont">&#xe600;</i> 报名</a></span> <span class="r">共有数据：<strong>{{count($lists)}}</strong> 条</span> </div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="80">ID</th>
					<th width="75">赛程名称</th>
					<th width="200">活动名称</th>
					<th width="80">团队名称</th>
					<th width="200">队员姓名</th>
					<th width="75">报名时间</th>
					<th width="120">操作</th>
				</tr>
			</thead>
			<tbody>
				
				@foreach($lists as $val)
				<tr class="text-c">
					<td><input type="checkbox" value="{{$val->id}}" name="id"></td>
					<td>{{$val->id}}</td>
					<td>{{$val->title}}</td>
					<td>
						@if($val->actives)
							@foreach($val->actives as $k1=>$v1)
								{{$v1}} |
							@endforeach
						@endif
					</td>
					<td>
						@if(isset($val->team_name))
							<a title="查看" href="javascript:;" onclick="article_edit('参赛团队','{{ URL::route("manage.team.detail",['id'=>$val->team_id]) }}','','500','400')" class="ml-5" style="text-decoration:none">{{$val->team_name}}</a>
						@else
							暂无
						@endif
					</td>
					<td>
						@foreach($val->players as $player)
							<u style="cursor:pointer" class="text-primary" onclick="member_show('{{$player['name']}}','{{ URL::route('manage.user.show',['id'=>$player['id']]) }}','10001','360','400')">{{$player['name']}}</u>
						@endforeach
					</td>
					<td>{{$val->created_at}}</td>
					<td class="f-14 td-manage">
						<a style="text-decoration:none" class="ml-5" onClick="article_edit('报名编辑','{{ URL::route('manage.schedule.signupdetail',['id'=>$val->id]) }}','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
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
	  {"orderable":false,"aTargets":[0,5]}// 不参与排序的列
	]
});

function member_show(title,url,id,w,h){
	layer_show(title,url,w,h);
}

</script> 
@endsection