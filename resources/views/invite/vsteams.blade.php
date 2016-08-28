@extends('layout2')
@section('body')
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 赛程管理 <span class="c-gray en">&gt;</span> 团队排名 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container">
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="200">团队一</th>
					<th width="30">分数</th>
					<th width="80">VS</th>
					<th width="200">团队二</th>
					<th width="30">分数</th>
					<th width="60">操作</th>
				</tr>
				</thead>
				<tbody>
					@if(isset($team_scores))
						<input name="id" value="{{$team_scores->id}}" type="hidden">
						<tr class="text-c">
							<td><input name="team_one_id" type="hidden" value="{{$team_scores->team_one_id}}">{{$team_scores->team_one_name}}</td>
							<td><input name="team_one_score" value="{{$team_scores->team_one_score}}"></td>
							<td>VS</td>
							<td><input name="team_two_id" type="hidden" value="{{$team_scores->team_two_id}}">{{$team_scores->team_two_name}}</td>
							<td><input name="team_two_score" value="{{$team_scores->team_two_score}}"></td>
							<td class="f-14 td-manage">
								<a style="text-decoration:none" class="ml-5" onClick="update(this)" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe632;</i></a>
								<a style="text-decoration:none" class="ml-5" onClick="del(this,'')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
							</td>
						</tr>
					@else
						<tr class="text-c">
							@if($team_one)
								<td>
									<button class="btn btn-success" onClick="add_team(this)" type="button">选择团队</button>
									<input name="team_one_id" type="hidden" attr="id" value="{{$team_one->id}}">
									<input name="" attr="name"  value="{{$team_one->name}}" readonly>
								</td>
							@else
								<td>
									<button class="btn btn-success" onClick="add_team(this)" type="button">选择团队</button>
									<input name="team_one_id" type="hidden" attr="id"  value="">
									<input name=""  attr="name"  value="">
								</td>
							@endif
							<td><input name="team_one_score" value=""></td>
							<td>VS</td>
							@if($team_two)
								<td>
									<button class="btn btn-success" onClick="add_team(this)" type="button">选择团队</button>
									<input name="team_two_id" type="hidden" attr="id" value="{{$team_two->id}}">
									<input name="" value="{{$team_two->name}}" attr="name" readonly>
								</td>
							@else
								<td>
									<button class="btn btn-success" onClick="add_team(this)" type="button">选择团队</button>
									<input name="team_two_id" attr="id" type="hidden" value="">
									<input name=""  attr="name"  value="">
								</td>
							@endif
							<td><input name="team_two_score" value=""></td>
							<td class="f-14 td-manage">
								<a style="text-decoration:none" class="ml-5" onClick="update(this)" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe632;</i></a>
								<a style="text-decoration:none" class="ml-5" onClick="del(this,'')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
							</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<input name="schedule_id" id="schedule_id" type="hidden" value="{{$schedule_id}}">
@endsection
@section('js')
	<script type="text/javascript">

		function update(obj){
			var data = $(obj).parents('tr');
			$.ajax({
				type: "POST",
				url: "{{ URL::route('invite') }}",
				data: {
					schedule_id : $('#schedule_id').val(),
					team_one_id : data.find('input[name="team_one_id"]').val(),
					team_one_score : data.find('input[name="team_one_score"]').val(),
					team_two_id : data.find('input[name="team_two_id"]').val(),
					team_two_score : data.find('input[name="team_two_score"]').val()
				},
				dataType: "json",
				success: function(data){
					alert('保存成功');
				},
				error:function (data) {
					alert('参数有误');
				}
			});
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
				url: "{{ URL::route('invite') }}",
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

		function add_team(obj){
			$('td').attr('select','');
			$(obj).parents('td').attr('select','true');
			var url = '{{ URL::route('manage.team.search')}}';
			var title = '添加团队';
			layer_show(title,url,900,500,obj);
		}

	</script>
@endsection