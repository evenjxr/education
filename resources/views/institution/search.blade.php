@extends('layout2')
@section('body')
	<div class="page-container">
		<div class="text-c">
			<form method="get" action="{{ URL::route('manage.team.search') }}">
				<input type="text" class="input-text" style="width:250px" placeholder="团队名称" id="" name="keyword">
				<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户</button>
			</form>
		</div>
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="40">ID</th>
					<th width="120">团队姓名</th>
					<th width="100">队员一</th>
					<th width="100">队员二</th>
					<th width="100">队员三</th>
					<th width="100">队员四</th>
					<th width="140">创建时间</th>
				</tr>
				</thead>
				<tbody>
				@foreach($lists as $val)
					<tr class="text-c">
						<td><input type="checkbox" value="{{$val->id}}" name="id"></td>
						<td>{{$val->id}}</td>
						<td>{{$val->name}}</td>
						<td>
							<u style="cursor:pointer" class="text-primary" onclick="member_show('张三','{{ URL::route('manage.user.show',['id'=>$val->player_one_id]) }}','10001','360','400')">{{$val->player_one_name}}</u>
						</td>
						<td>
							<u style="cursor:pointer" class="text-primary" onclick="member_show('张三','{{ URL::route('manage.user.show',['id'=>$val->player_two_id]) }}','10001','360','400')">{{$val->player_two_name}}</u>
						</td>
						<td>
							<u style="cursor:pointer" class="text-primary" onclick="member_show('张三','{{ URL::route('manage.user.show',['id'=>$val->player_three_id]) }}','10001','360','400')">{{$val->player_three_name}}</u>
						</td>
						<td>
							<u style="cursor:pointer" class="text-primary" onclick="member_show('张三','{{ URL::route('manage.user.show',['id'=>$val->player_four_id]) }}','10001','360','400')">{{$val->player_four_name}}</u>
						</td>
						<td>{{$val->created_at}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div style="margin: 10px; float: right;">
		<button  class="btn btn-primary radius" type="button" onclick="get_team()">确定</button>
		<button  class="btn btn-danger radius" type="button" onclick="layer_close()">取消</button>
	</div>
@endsection
@section('js')
	<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>

	<script type="text/javascript">
		$('.table-sort').dataTable({
			"aaSorting": [[ 1, "desc" ]],//默认第几个排序
			"bStateSave": true,//状态保存
			"aoColumnDefs": [
				//{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
				{"orderable":false,"aTargets":[0,5]}// 不参与排序的列
			]
		});

		/*用户-查看*/
		function member_show(title,url,id,w,h){
			layer_show(title,url,w,h);
		}

		function get_team()
		{
			var obj = $(window.parent.document).contents().find('td[select="true"]');
			var getVal = $('input[name="id"]:checked');
			obj.find('input[attr="id"]').val(getVal.val());
			obj.find('input[attr="name"]').val(getVal.parent().next().next().html());
			layer_close();
		}

	</script>
@endsection