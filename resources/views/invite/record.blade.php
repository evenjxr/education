@extends('layout2')
@section('body')
	<div class="page-container">
		<div class="text-c">
			<form method="get" action="{{ URL::route('manage.invite.search') }}">
				日期范围：
				<input type="text" name="start" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" id="logmin" class="input-text Wdate" style="width:120px;">
				-
				<input type="text" name="end" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d'})" id="logmax" class="input-text Wdate" style="width:120px;">
				<input type="text" name="keyword" id="" placeholder=" 活动名称" style="width:250px" class="input-text">
				<button id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜活动</button>
			</form>
		</div>
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="80">ID</th>
					<th width="80">姓名</th>
					<th width="80">账号类型</th>
					<th width="140">注册时间</th>
					<th width="140">下单数量</th>
					<th width="140">下单金额</th>
				</tr>
				</thead>
				<tbody>
				@foreach($lists as $val)
					<tr class="text-c">
						<td><input type="checkbox" value="{{$val->id}}" name="id"></td>
						<td>{{$val->id}}</td>
						<td>{{$val->truename}}</td>
						<td>{{$val->type}}</td>
						<td>{{$val->created_at}}</td>
						<td>{{$val->order_num}}</td>
						<td>{{$val->order_amount}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div style="margin: 10px; float: right;">
		<button  class="btn btn-primary radius" type="button" onclick="get_active()">确定</button>
		<button  class="btn btn-danger radius" type="button" onclick="layer_close()">取消</button>
	</div>
@endsection
@section('js')
	<script type="text/javascript" src="/lib/My97DatePicker/WdatePicker.js"></script>
	<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
	<script type="text/javascript">
		$('.table-sort').dataTable({
			"aaSorting": [[ 1, "desc" ]],//默认第几个排序
			"bStateSave": true,//状态保存
			"aoColumnDefs": [
				// {"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
				{"orderable":false,"aTargets":[0,2]}// 不参与排序的列
			]
		});
	</script>
@endsection