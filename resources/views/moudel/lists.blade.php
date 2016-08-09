@extends('layout2')
@section('body')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 模块列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>


<div class="cl pd-5 bg-1 bk-gray mt-20"> 
	<span class="l">
		<a class="btn btn-primary radius" data-title="添加模块" _href="{{ URL::route('manage.moudel.delete') }}" onclick=moudel_add('添加模块','{{ URL::route("manage.moudel.add")}}','500','250') href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加模块
		</a>
	</span> 
	<span class="r">共有数据：<strong>{{count($lists)}}</strong> 条</span> </div>

<div class="page-container">
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="9">权限模块列表</th>
			</tr>
			<tr class="text-c">
				<th width="25"><input type="checkbox" name="" value=""></th>
				<th width="40">ID</th>
				<th width="150">版块名称</th>
				<th width="90">节点名称</th>
				<th width="100">操作</th>
			</tr>
		</thead>
		<tbody>
                                   
			@foreach($lists as $val)
			<tr class="text-c">
				<td><input type="checkbox" value="1" name=""></td>
				<td>{{$val->id}}</td>
				<td>{{$val->name}}</td>
				<td>{{$val->short_name}}</td>
				<td class="td-manage">
					<a title="编辑" href="javascript:;" onclick="moudel_edit('模块编辑','{{ URL::route("manage.moudel.detail",["id"=>$val->id ])}}',{{$val->id}},'500','300')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
					<a title="删除" href="javascript:;" onclick="moudel_del(this,{{$val->id}})" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>
</div>
@endsection
@section('js')
<script type="text/javascript">
	/*管理员-增加*/
	function moudel_add(title,url,w,h){
		layer_show(title,url,w,h);
	}
	/*管理员-删除*/
	function moudel_del(obj,id){
		layer.confirm('确认要删除吗？',function(index){
			doDelete(id);
			$(obj).parents("tr").remove();
			layer.msg('已删除!',{icon:1,time:1000});
		});
	}
	/*管理员-编辑*/
	function moudel_edit(title,url,id,w,h){
		layer_show(title,url,w,h);
	}
	function doDelete(ids){
		$.ajax({
			type: "POST",
			url: "{{ URL::route('manage.moudel.delete') }}",
			data: {
				ids: ids,
			},
			dataType: "data",
			success: function(data){
			}
		});
		return true;
	}
</script> 
@endsection