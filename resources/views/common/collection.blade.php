@extends('layout2')
@section('body')
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户管理 <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>	<div class="page-container">
		<div class="text-c">
			<form method="get" action="{{ URL::route('manage.user.lists') }}">
				<input type="text" class="input-text" style="width:250px" placeholder="微信昵称,姓名,手机号,身份证" id="" name="keyword">
				<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户</button>
			</form>
		</div>
		<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a class="btn btn-primary radius" data-title="添加用户" _href="{{ URL::route('manage.user.add') }}" onclick=article_add('添加资讯','{{ URL::route("manage.user.add") }}') href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加用户</a></span> <span class="r">共有数据：<strong>{{count($lists)}}</strong> 条</span> </div>
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
					<th width="140">加入时间</th>
				</tr>
				</thead>
				<tbody>
				@foreach($lists as $val)
					<tr class="text-c">
						<td><input type="checkbox" value="1" name=""></td>
						<td>{{$val->id}}</td>
						<td>
							<u style="cursor:pointer" class="text-primary" onclick="member_show('张三','{{ URL::route('manage.user.show',['id'=>$val->id]) }}','10001','360','400')">{{$val->name}}</u>
						</td>
						<td>
							<u style="cursor:pointer" class="text-primary" onclick="member_show('张三','{{ URL::route('manage.user.show',['id'=>$val->id]) }}','10001','360','400')">{{$val->nickname}}</u>
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
						<td>{{$val->created_at}}</td>
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
	</script>
@endsection