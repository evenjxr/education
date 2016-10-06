@extends('layout2')
@section('body')
	<div class="page-container">
		<div class="text-c">
			<form method="get" action="{{ URL::route('manage.teacher.search') }}">
				<input type="text" class="input-text" style="width:250px" placeholder="手机号,姓名" id="" name="keyword">
				<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户</button>
			</form>
		</div>
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="100">真是姓名</th>
					<th width="100">年龄</th>
					<th width="60">学校</th>
					<th width="120">手机</th>
					<th width="90">地址</th>
				</tr>
				</thead>
				<tbody>
				@foreach($lists as $val)
					<tr class="text-c">
						<td><input type="checkbox" value="{{$val->id}}" name="id"></td>
						<td>{{$val->truename ? :''}}</td>
						<td>{{$val->age}}</td>
						<td>{{$val->school_name}}</td>
						<td>{{$val->mobile}}</td>
						<td>{{$val->address}}</td>
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
			var id = $(window.parent.document).contents().find('#teacher_id');
			var name = $(window.parent.document).contents().find('#teacher_name');
			var obj = $('input[name="id"]:checked');
			id.val(obj.val());
			name.val(obj.parent().next().html());
			layer_close();
		}

	</script>
@endsection