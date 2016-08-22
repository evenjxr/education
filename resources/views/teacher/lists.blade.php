@extends('layout2')
@section('body')
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户管理 <span class="c-gray en">&gt;</span> 老师列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>	<div class="page-container">
		<div class="text-c">
			<form method="get" action="{{ URL::route('manage.teacher.lists') }}">
				<input type="text" class="input-text" style="width:250px" placeholder="姓名,手机号" id="" name="keyword">
				<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜老师</button>
			</form>
		</div>
		<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a class="btn btn-primary radius" data-title="添加老师" _href="{{ URL::route('manage.teacher.add') }}" onclick=article_add('添加资讯','{{ URL::route("manage.teacher.add") }}') href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加老师</a></span> <span class="r">共有数据：<strong>{{count($lists)}}</strong> 条</span> </div>
		<div class="mt-20">
			<table class="table table-border table-bordered table-bg table-hover table-sort">
				<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="40">ID</th>
					<th width="100">账号</th>
					<th width="100">真是姓名</th>
					<th width="100">年龄</th>
					<th width="60">学校</th>
					<th width="120">手机</th>
					<th width="90">地址</th>
					<th width="140">加入时间</th>
					<th width="100">操作</th>
				</tr>
				</thead>
				<tbody>
				@foreach($lists as $val)
					<tr class="text-c">
						<td><input type="checkbox" value="1" name=""></td>
						<td>{{$val->id}}</td>
						<td><u style="cursor:pointer" class="text-primary" onclick="member_show('张三','{{ URL::route('manage.teacher.show',['id'=>$val->id]) }}','10001','360','400')">{{$val->username}}</u></td>
						<td>{{$val->turename ? :''}}</td>
						<td>{{$val->age}}</td>
						<td>{{$val->school_name}}</td>
						<td>{{$val->mobile}}</td>
						<td>{{$val->address?$addresses[$val->address]:''}}</td>
						<td>{{$val->created_at}}</td>
						<td class="td-manage">
							<a onClick="member_sms(this,{{$val->id}})" href="javascript:;" title="发送短信" style="text-decoration:none" class="ml-5" ><i class="Hui-iconfont">&#xe68a;</i></a>
							<a title="编辑" href="javascript:;" onclick="article_edit('管理员编辑','{{ URL::route("manage.teacher.detail",['id'=>$val->id]) }}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
							<a title="删除" href="javascript:;" onclick="article_del(this,'{{$val->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
		/*用户-查看*/
		function member_show(title,url,id,w,h){
			layer_show(title,url,w,h);
		}

		function member_sms(obj,id){
			layer.confirm('确认要发送短信吗？',function(index){
				$.ajax({
					type: 'post',
					url: "{{ URL::route('manage.teacher.sms') }}",
					data: {
						id: id
					},
					dataType: "json",
					success: function(data){
					}
				});
				layer.msg('已发送!',{icon:1,time:1000});
			});
		}
	</script>
@endsection