@extends('layout2')
@section('body')
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 赛程管理 <span class="c-gray en">&gt;</span> 团队排名 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container">
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="25">排名</th>
					<th width="80">队名</th>
					<th width="80"><i class="Hui-iconfont">&#xe697;</i>     胜场</th>
					<th width="80"><i class="Hui-iconfont">&#xe66e;</i>     负场</th>
					<th width="60">操作</th>
				</tr>
				</thead>
				<tbody>
				@foreach($team as $key=>$val)
					<tr class="text-c">
						<td>{{$key+1}}</td>
						<td>{{$val->name}}</td>
						<td class="victory">@if($val->victory) {{$val->victory}} @else 0 @endif</td>
						<td class="lose">@if($val->lose) {{$val->lose}} @else 0 @endif</td>
						<td class="f-14 td-manage">
							<a style="text-decoration:none" class="ml-5" onClick="article_edit('比赛历史','{{ URL::route('manage.score.teamhistory',['team_id'=>$val->id,'schedule_id'=>$schedule_id]) }}','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
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
			"aaSorting": [[ 3, "desc" ]],//默认第几个排序
			"bStateSave": true,//状态保存
			"aoColumnDefs": [
				//{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
				{"orderable":false,"aTargets":[0]}// 不参与排序的列
			]
		});
	</script>
@endsection