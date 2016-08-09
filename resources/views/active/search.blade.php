@extends('layout2')
@section('body')
<div class="page-container">
	<div class="text-c"> 
		<form method="get" action="{{ URL::route('manage.active.search') }}">
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
					<th width="80">活动名称</th>
					<th width="80">活动类型</th>
					<th width="140">创建时间</th>
				</tr>
			</thead>
			<tbody>
				@foreach($lists as $val)
				<tr class="text-c">
					<td><input type="checkbox" value="{{$val->id}}" name="id"></td>
					<td>{{$val->id}}</td>
					<td class="text-l"><u style="cursor:pointer" class="text-primary" onClick="article_edit('查看','{{ URL::route('manage.active.detail',['id'=>$val->id]) }}','10002')" title="查看">{{$val->name}}</u></td>
					<td>{{ $channel[$val->channel] }}</td>
					<td>{{$val->created_at}}</td>
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
function get_active()
{
	var flag = $(window.parent.document).contents().find(".active");
	var selects = $('input[name="id"]:checked');
	selects.each(function(index,item){
		index = index+1;
		var html = '<div class="li"><input name="active_id[]" type="hidden" value="'+$(item).val()+'"><div class="row cl"><label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>活动'+index+'：</label><div class="formControls col-xs-8 col-sm-9"><input type="text" class="input-text" value="'+$(item).parents('tr').find('u').html()+'" style="width:300px; margin-right: 10px;" readonly="true"><button class="btn btn-danger" onClick="del_active(this)" type="button">移除</button></div></div></div>';
		flag.append(html);
	});
	layer_close();
}

</script> 
@endsection