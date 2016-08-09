@extends('layout2')
@section('body')
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 赛程管理 <span class="c-gray en">&gt;</span> 团队排名 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container">
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="25">序号</th>
					<th width="25">团队一</th>
					<th width="80">分数</th>
					<th width="80">VS</th>
					<th width="80">团队二</th>
					<th width="80">分数</th>
					<th width="60">操作</th>
				</tr>
				</thead>
				<tbody>

				@foreach($history as $key=>$val)
					<tr class="text-c">
						<td>{{$key}}</td>
						<td><input name="team_one_id" type="hidden" value="{{$val->team_one_id}}">{{$val->team_one_name}}</td>
						<td><input name="team_one_score" value="{{$val->team_one_score}}"></td>
						<td>VS</td>
						<td><input name="team_two_id" type="hidden" value="{{$val->team_two_id}}">{{$val->team_two_name}}</td>
						<td><input name="team_two_score" value="{{$val->team_two_score}}"></td>
						<td class="f-14 td-manage">
							<a style="text-decoration:none" class="ml-5" onClick="update(this)" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
							<a style="text-decoration:none" class="ml-5" onClick="del(this,{{$val->id}})" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
						</td>
					</tr>
				@endforeach
					<tr class="text-c">
						<td>new</td>
						<td><input name="team_one_id" type="hidden" value="{{$team_id}}">{{$team_name}}</td>
						<td><input name="team_one_score" value=""></td>
						<td>VS</td>
						<td>
							<input type="hidden" value="" select="team_id" name="team_two_id">
							<input value="" select="team_name" name="team_two_name" onClick="add_team('用户团队','{{ URL::route('manage.team.search')}}','900','500')" readonly>
						</td>
						<td>
							<input name="team_two_score" value="">
						</td>
						<td class="f-14 td-manage">
							<a style="text-decoration:none" class="ml-5" onClick="update(this)" href="javascript:;" title="新增">新增</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<input name="schedule_id" id="schedule_id" type="hidden" value="{{$schedule_id}}">
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

		function update(obj){
			var data = $(obj).parents('tr');
			$.ajax({
				type: "POST",
				url: "{{ URL::route('manage.score.updateteamhistory') }}",
				data: {
					schedule_id : $('#schedule_id').val(),
					team_one_id : data.find('input[name="team_one_id"]').val(),
					team_one_score : data.find('input[name="team_one_score"]').val(),
					team_two_id : data.find('input[name="team_two_id"]').val(),
					team_two_score : data.find('input[name="team_two_score"]').val()
				},
				dataType: "json",
				success: function(data){
					refresh();
				},
				error:function (data) {
					alert('参数有误');
				}
			});
			return true;
		}

		/*资讯-删除*/
		function del(obj,id){
			layer.confirm('确认要删除吗？',function(index){
				$(obj).parents("tr").remove();
				doDelete(id);
				layer.msg('已删除!',{icon:1,time:1000});
			});
		}

		function doDelete(id){
			$.ajax({
				type: "POST",
				url: "{{ URL::route('manage.score.delteamhistory') }}",
				data: {
					id: id,
					schedule_id: $('#schedule_id').val()
				},
				dataType: "json",
				success: function(data){
				}
			});
			return true;
		}

		function add_team(title,url,w,h){
			layer_show(title,url,w,h);
		}

	</script>
@endsection