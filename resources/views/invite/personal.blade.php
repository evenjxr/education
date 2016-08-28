@extends('layout2')
@section('css')
	<style type="text/css" xmlns="http://www.w3.org/1999/html">
		td{
			margin:0;
			padding:0;
		}
		input {
			margin: 2px auto;
			width: 45px;
		}
		.table th, .table td {
			padding: 4px;
		}
	</style>
@endsection
@section('body')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 赛程管理 <span class="c-gray en">&gt;</span> 赛程列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="cl pd-5 bg-1 bk-gray mt-20">
		<span class="l">
			<a class="btn btn-danger radius" onclick=ajax_post() style="margin-right:20px;"><i class="Hui-iconfont">&#xe600;</i> 保存  </a></span></a>
			<a class="btn btn-primary radius" data-title="报名" onclick=signUp('报名','{{ URL::route("manage.schedule.sign",['id'=>$schedule->id]) }}',900,500)><i class="Hui-iconfont">&#xe600;</i> 报名  </a>
		</span>
	</div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th rowspan="2">序号</th>
					<th rowspan="2">姓名</th>
					<th rowspan="2">性别</th>
					<th rowspan="2">年龄</th>
					<th rowspan="2">编号</th>
					@foreach($active as $key=>$value)
						@if($value->standard == 'best_of_three')
							<th colspan="3">{{$value->name}}</th>
						@endif
						@if($value->standard == 'num_time')
							<th colspan="2">{{$value->name}}</th>
						@endif
						@if($value->standard == 'num')
							<th>{{$value->name}}</th>
						@endif
						@if($value->standard == 'time')
							<th>{{$value->name}}</th>
						@endif
					@endforeach
					<th rowspan="2">操作</th>
				</tr>
				<tr>
					@foreach($active as $key=>$value)
						@if($value->standard == 'best_of_three')
							<th>一次</th>
							<th>二次</th>
							<th>三次</th>
						@endif
						@if($value->standard == 'num_time')
							<th>个数</th><th>时间</th>
						@endif
						@if($value->standard == 'num')
							<th>个数</th>
						@endif
						@if($value->standard == 'time')
							<th>时间</th>
						@endif
					@endforeach
				</tr>

			</thead>
			<tbody>
				@foreach($user as $k=>$v)
				<tr>
					<td>{{$k+1}}</td>
					<td>{{$v->name}}</td>
					<td>@if($v->sex==1)男 @else 女 @endif</td>
					<td>{{$v->age}}</td>
					<td>{{$v->id}}</td>
					@foreach($active as $key=>$value)
						@if($value->standard == 'best_of_three')
							<td @if (@in_array($value->id,$v->actives)) class="unselect" @endif>
								<input name="first" value="{{ isset($data[$v->id][$value->id]['first']) ? $data[$v->id][$value->id]['first']: null }}" active_id="{{$value->id}}">
							</td>
							<td  @if (@in_array($value->id,$v->actives)) class="unselect" @endif>
								<input name="second" value="{{ isset($data[$v->id][$value->id]['second'])  ? $data[$v->id][$value->id]['second']: null }}" active_id="{{$value->id}}">
							</td>
							<td  @if (@in_array($value->id,$v->actives)) class="unselect" @endif>
								<input name="three" value="{{ isset($data[$v->id][$value->id]['three'])  ? $data[$v->id][$value->id]['three']: null }}" active_id="{{$value->id}}">
							</td>
						@endif
						@if($value->standard == 'num_time')
							<td  @if (@in_array($value->id,$v->actives)) class="unselect" @endif>
								<input name="num" value="{{ isset($data[$v->id][$value->id]['num']) ? $data[$v->id][$value->id]['num'] : null }}" active_id="{{$value->id}}">
							</td>
							<td  @if (@in_array($value->id,$v->actives)) class="unselect" @endif>
								<input name="time" value="{{isset($data[$v->id][$value->id]['time'])  ? $data[$v->id][$value->id]['time']: null }}" active_id="{{$value->id}}">
							</td>
						@endif
						@if($value->standard == 'num')
							<td  @if (@in_array($value->id,$v->actives)) class="unselect" @endif>
								<input name="num" value="{{ isset($data[$v->id][$value->id]['num'])  ? $data[$v->id][$value->id]['num'] : null }}" active_id="{{$value->id}}">
							</td>
						@endif
						@if($value->standard == 'time')
							<td  @if (@in_array($value->id,$v->actives)) class="unselect" @endif>
								<input name="time" value="{{ isset($data[$v->id][$value->id]['time'])  ? $data[$v->id][$value->id]['time'] : null }}" active_id="{{$value->id}}">
							</td>
						@endif
					@endforeach
					<td><a style="text-decoration:none" class="ml-5" onClick="cancelSignUp(this,'{{$schedule->id}}','{{$v->id}}')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection
@section('js')
	<script type="text/javascript">

		$('td.unselect input').attr('disabled',true).css('background','#eee');

		var a = 10*60*1000;
		setInterval("ajax_post()",a);
		function ajax_post(){
			var data = '';
			var tr = $('tbody').children('tr');
			tr.each(function () {
					var length = $(this).children('td').length;
					for(i=5;i<length-1;i++){
						var trObj = $(this).find('td:eq('+i+')').children('input');
						data += $(this).find('td:eq(4)').text()+','+trObj.attr('active_id')+','+trObj.attr('name')+','+trObj.val()+'|';
					}
				console.log(data);
			});
			$.ajax({
				type: "POST",
				url: '{{URL::route('invite')}}',
				data: {
					schedule_id :'{{$schedule->id}}',
					data : data
				},
				dataType: "json",
				success: function(data){
					alert('已保存');
				}
			});

		}
		function signUp(title,url,w,h){
			layer_show(title,url,w,h);
		}

		function cancelSignUp(obj,schedule_id,user_id){
			layer.confirm('确认要删除吗？',function(index){
				$(obj).parents("tr").remove();
				$.ajax({
					type: "POST",
					url: '{{URL::route('manage.schedule.cancel')}}',
					data: {
						schedule_id :schedule_id,
						user_id : user_id
					},
					dataType: "json",
					success: function(data){
						alert('已删除');
					}
				});
				layer.msg('已删除!',{icon:1,time:1000});
			});
		}
	</script>
@endsection