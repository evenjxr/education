@extends('layout2')
@section('body')
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 定单管理 <span class="c-gray en">&gt;</span> 订单列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>	<div class="page-container">
		<div class="text-c">
			<form method="get" action="{{ URL::route('manage.equipment.lists') }}">
				<input type="text" class="input-text" style="width:250px" placeholder="手机号" id="" name="keyword">
				<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜订单</button>
			</form>
		</div>
		<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="r">共有数据：<strong>{{count($lists)}}</strong> 条</span> </div>
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="40">ID</th>
					<th width="110">预约电话</th>
					<th width="70">联系人姓名</th>
					<th width="100">老师/学生</th>
					<th width="180">地址</th>
					<th width="50">推荐类型</th>
					<th width="140">下单时间</th>
					<th width="80">状态</th>
					<th width="140">操作</th>
				</tr>
				</thead>
				<tbody>
				@foreach($lists as $val)
					<tr class="text-c">
						<td>{{$val->id}}</td>
						<td>{{$val->mobile}}</td>
						<td>{{$val->link_name}}</td>
						<td>{{$val->member_type}}</td>
						<td>{{$val->city}} , {{$val->address_detail}}</td>
						<td>{{$val->recommend_type}}</td>
						<td>{{$val->created_at}}</td>
						<td>				
							@if($val->status==0)
							<span class="label label-danger radius">取消订单</span>
							@elseif($val->status==1)
							<span class="label label-success radius">提交订单</span>
							@elseif($val->status==2)
							<span class="label label-success radius">已支付</span>
							@elseif($val->status==3)
							<span class="label label-info radius">退单</span>
							@endif
						</td>
						<td class="td-manage">
							<a class="c-primary" onClick="shiftStatus({{$val->id}},0)" href="javascript:;" title="取消订单">取消订单</a>
							<a style="text-decoration:none" onClick="shiftStatus({{$val->id}},2)" href="javascript:;" title="已支付">已支付</a>
							<a style="text-decoration:none" onClick="shiftStatus({{$val->id}},3)" href="javascript:;" title="退单">退单</a>
							<a title="编辑" href="javascript:;" onclick="article_edit('管理员编辑','{{ URL::route("manage.equipment.detail",['id'=>$val->id]) }}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
@section('js')
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
		     	url: "{{ URL::route('manage.equipment.auth') }}",
		     	data: {
		     			id: id, 
		     			status: status
		     		},
		     	dataType: "json",
		     	success: function(data){
		     		layer.msg('操作成功!',{icon:1,time:1000});
		        }
		    });
		}

	</script>


@endsection