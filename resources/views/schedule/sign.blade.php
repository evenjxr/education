@extends('layout2')
@section('body')
<article class="page-container">
	<form action="{{ URL::route('manage.schedule.dosignup') }}" method="post" class="form form-horizontal" id="form-manage-signup-add">
		<div class="row cl personal">
			<label class="form-label col-xs-4 col-sm-2">
				<button class="btn btn-success" onClick="add_user('选择用户','{{ URL::route('manage.user.search')}}','900','580')" type="button"><i class="Hui-iconfont">&#xe665;</i> 注册用户</button>
			</label>
			<div class="formControls col-xs-8 col-sm-10">
				<input type="hidden" value="" placeholder="" select="user_id" name="user_id">
				<input type="text" style="width: 300px;" class="input-text" value="" placeholder="" select="user_name" name="user_name" datatype="*4-16" nullmsg="角色名不得为空">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">赛程活动选择：</label>
			<div class="formControls col-xs-8 col-sm-10">
				<dl class="permission-list {{$schedule->channel}}">
					<dt>
						<label>
							<input type="checkbox" value="{{$schedule->id}}" name="schedule_id" id="user-Character-0">
							{{$schedule->title}}  {{$schedule->sign_num}}/{{$schedule->max_num}} </label> <label style="float: right;color: #999;">{{$schedule->start}}</label>
					</dt>
					<dd>
						<dl class="cl permission-list2">
							<dt>
								<label class="">
									<input type="checkbox" value="" id="">
									全选 &nbsp;&nbsp;&nbsp;&nbsp;<i class="Hui-iconfont">&#xe6c0;</i> </label>
							</dt>
							<dd>
								@foreach($schedule->actives as $k=>$v)
								<label style="float: left;">
									<input type="checkbox"  value="{{$v->id}}" name="active[]">
									{{$v->name}}
								</label>
								@endforeach
							</dd>
						</dl>
					</dd>
				</dl>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"></label>
			<div class="col-xs-8 col-sm-10">
				<button type="submit" class="btn btn-success radius" id="admin-role-save" name="" onclick="send()"><i class="icon-ok"></i> 确定</button>
				<button onClick="removeIframe();" class="btn btn-default radius" type="button">取消</button>
			</div>
		</div>
		<input name="id" value="{{$schedule->id}}" type="hidden">
	</form>
</article>
@endsection
@section('js')
	<script type="text/javascript" src="/lib/My97DatePicker/WdatePicker.js"></script>
	<script type="text/javascript">
$(function(){
	$(".permission-list dt input:checkbox").click(function(){
		var that = this;
		$('input:checkbox').each(function () {
			$(this).prop("checked",false);
		});

		$(that).closest("dl").find("dd input:checkbox").prop("checked",$(that).prop("checked",true));
	});

	$(".permission-list2 dt input:checkbox").click(function(){
		$(this).parents(".permission-list").find("dt").find("input:checkbox").prop("checked",true);
	});
	$(".permission-list2 dd input:checkbox").click(function(){
		var l =$(this).parent().parent().find("input:checked").length;
		var l2=$(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
		var l3 =$(this).parent().parent().find("input").length;
		var that = this;
		$(this).parents('.permission-list').siblings().each(function () {
			//$(this).closest("dl").find("dd input:checkbox").prop("checked",false);
			$(this).find('input:checkbox').each(function () {
				$(this).prop("checked",false);
			});
		});

		if($(that).prop("checked")){
			//$(this).closest("dl").find("dt input:checkbox").prop("checked",true);
			$(that).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",true);
		}
		else{
			if(l==0){
				$(that).closest("dl").find("dt input:checkbox").prop("checked",false);
			}
			if(l2==0){
				$(that).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",false);
			}
		}

		if(l==l3){
			$(that).parents('.permission-list2').find('dt input').prop('checked',true);
		} else {
			$(that).parents('.permission-list2').find('dt input').prop('checked',false);
		}
	});


	$('.team').css({display:'none'});
	$('select[name="channel"]').change(function(){
		if ( $(this).val() == 'personal') {
			$('.personal').css('display','block');
		} else {
			$('.personal').css('display','none');
		}
		if ( $(this).val() == 'team') {
			//alert($(this).val());
			$('.team').css('display','block');
		} else {
			$('.team').css('display','none');
		}
	});

});
function add_user(title,url,w,h){
	layer_show(title,url,w,h);
}
function add_team(title,url,w,h){
	layer_show(title,url,w,h);
}
</script>
@endsection