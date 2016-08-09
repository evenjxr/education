@extends('layout2')
@section('body')
<div class="page-container">
	<form method="get" action="{{ URL::route('manage.library.videosearchlists') }}">
		<div class="text-c">
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
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="80">ID</th>
					<th width="100">视频名称</th>
					<th width="100">URL</th>
					<th width="150">更新时间</th>
				</tr>
			</thead>
			<tbody>
				@foreach($videos as $key=>$val)
				<tr class="text-c">
					<td><input type="checkbox" value="{{$val->id}}" name="id"></td>
					<td>{{$key}}</td>
					<td>{{$val->name}}</td>
					<td class="url">{{$val->url}}</td>
					<td>{{$val->created_at}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
<div style="margin: 10px; float: right;">
	<button  class="btn btn-primary radius" type="button" onclick="get_url()">确定</button>
	<button  class="btn btn-danger radius" type="button" onclick="layer_close()">取消</button>
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
	function get_url()
	{
		var obj = $(window.parent.document).contents().find('#url');
		var url = $('input[name="id"]:checked').parents('tr').find('.url').text();
		obj.val(url);
		layer_close();
	}
</script>
@endsection