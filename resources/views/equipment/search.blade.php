@extends('layout2')
@section('body')
	<div class="page-container">
		<div class="text-c">
			<form method="get" action="{{ URL::route('manage.user.search') }}">
				<input type="text" class="input-text" style="width:250px" placeholder="微信昵称,姓名,手机号,身份证" id="" name="keyword">
				<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户</button>
			</form>
		</div>
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="40">ID</th>
					<th width="100">姓名</th>
					<th width="100">昵称</th>
					<th width="60">性别</th>
					<th width="120">手机</th>
					<th width="90">城市</th>
				</tr>
				</thead>
				<tbody>
				@foreach($lists as $val)
					<tr class="text-c">
						<td><input type="radio" value="{{$val->id}}" name="id"></td>
						<td>{{$val->id}}</td>
						<td>
							<u style="cursor:pointer" class="text-primary" onclick="member_show('{{$val->name}}','{{ URL::route('manage.user.show',['id'=>$val->id]) }}','10001','360','400')">{{$val->name}}</u>
						</td>
						<td>
							<u style="cursor:pointer" class="text-primary" onclick="member_show('{{$val->name}}','{{ URL::route('manage.user.show',['id'=>$val->id]) }}','10001','360','400')">{{$val->nickname}}</u>
						</td>
						<td>
							@if($val->sex==1)
								男
							@else
								女
							@endif
						</td>
						<td>{{$val->mobile}}</td>
						<td>{{$val->city}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div style="margin: 10px; float: right;">
		<button  class="btn btn-primary radius" type="button" onclick="get_user()">确定</button>
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

		function get_user()
		{
			var id = $(window.parent.document).contents().find('input[select="user_id"]');
			var name = $(window.parent.document).contents().find('input[select="user_name"]');
			var obj = $('input[name="id"]:checked');
			id.val(obj.val());
			name.val(obj.parent().next().next().find('u').html());
			layer_close();
		}

	</script>
@endsection