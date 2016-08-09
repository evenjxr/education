@extends('layout2')
@section('body')
<div class="page-container">
	<form method="get" action="{{ URL::route('manage.library.picsearch') }}">
		<div class="text-c">
			<span class="select-box inline">
				<select name="moudel_id" class="select">
					<option value="">全部</option>
					@foreach($moudels as $key=>$val)
						<option value="{{$key}}">{{$val}}</option>
					@endforeach
				</select>
			</span>
			日期范围：
			<input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" id="logmin" class="input-text Wdate" style="width:120px;">
			-
			<input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d'})" id="logmax" class="input-text Wdate" style="width:120px;">
			<input type="text" name="" id="" placeholder=" 图片名称" style="width:250px" class="input-text">
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜图片</button>
		</div>
	</form>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="80">ID</th>
					<th width="100">组名</th>
					<th width="150">更新时间</th>
				</tr>
			</thead>
			<tbody>
				@foreach($piclists as $val)
				<tr class="text-c">
					<td>{{$val->id}}</td>
					<td>
						<a href="javascript:;" onClick="article_edit('图库预览','{{ URL::route('manage.library.picsearchdetail',['group_name'=>$val->group_name]) }}')">{{$val->group_name}}</a>
					</td>
					<td>{{$val->created_at}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
<div style="margin: 40px -100px 0 0; float: right;">
	<button  class="btn btn-danger radius" type="button" onclick="layer_close()">关闭</button>
</div>
@endsection
@section('js')
<script type="text/javascript" src="/static/h-ui/js/auth.js"></script>
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