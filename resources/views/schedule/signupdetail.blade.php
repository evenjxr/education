@extends('layout2')
@section('body')
<article class="page-container">
	<form method="get" action="{{ URL::route('manage.schedule.signlist') }}">
		<div class="text-c">
			赛程日期
			<input type="text" name="start" onfocus="WdatePicker()" id="logmin" class="input-text Wdate" style="width:120px;">
			-
			<input type="text" name="title" id="" placeholder="搜赛程" style="width:250px" class="input-text">
			<button id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜赛程</button>
		</div>
	</form>
	<form action="{{ URL::route('manage.schedule.signupupdate') }}" method="post" class="form form-horizontal" id="form-manage-signup-add">
		<input type="hidden" value="{{$tournament->id}}" placeholder="" name="id">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>比赛类型：</label>
			<div class="formControls col-xs-8 col-sm-10">
				<span class="select-box">
				<select name="channel" class="select">
					@if ($tournament->channel == 'personal')
						<option value="personal" selected="ture">个人赛</option>
						<option value="team">团队赛</option>
						@else
						<option value="personal">个人赛</option>
						<option value="team" selected="ture">团队赛</option>
					@endif
				</select>
				</span>
			</div>
		</div>
		<div class="row cl team" style="display: none;">
			<label class="form-label col-xs-4 col-sm-2">
				<button class="btn btn-success" onClick="add_team('用户团队','{{ URL::route('manage.team.search')}}','900','500')" type="button"><i class="Hui-iconfont">&#xe665;</i> 选择团队</button>
			</label>
			<div class="formControls col-xs-8 col-sm-10">
				@if($tournament->team_id)
					<input type="hidden" value="{{$tournament->team_id}}" placeholder="" select="team_id" name="team_id">
					<input type="text" style="width: 300px;" class="input-text" value="{{$tournament->team_name}}" placeholder="" select="team_name" name="team_name" datatype="*4-16" nullmsg="用户不得为空" readonly>
				@else
					<input type="hidden" value="" placeholder="" select="team_id" name="team_id">
					<input type="text" style="width: 300px;" class="input-text" value="" placeholder="" select="team_name" name="team_name" datatype="*4-16" nullmsg="用户不得为空" readonly>
				@endif
			</div>
		</div>

		<div class="row cl personal" style="display: none;">
			<label class="form-label col-xs-4 col-sm-2">
				<button class="btn btn-success" onClick="add_user('选择用户','{{ URL::route('manage.user.search')}}','900','580')" type="button"><i class="Hui-iconfont">&#xe665;</i> 注册用户</button>
			</label>
			<div class="formControls col-xs-8 col-sm-10">
				@if($tournament->user_id)
					<input type="hidden" value="{{$tournament->user_id}}" placeholder="" select="user_id" name="user_id">
					<input type="text" style="width: 300px;" class="input-text" value="{{$tournament->user_name}}" placeholder="" select="user_name" name="user_name" datatype="*4-16" nullmsg="角色名不得为空">
				@else
					<input type="hidden" value="" placeholder="" select="user_id" name="user_id">
					<input type="text" style="width: 300px;" class="input-text" value="" placeholder="" select="user_name" name="user_name" datatype="*4-16" nullmsg="角色名不得为空">
				@endif
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">赛程活动选择：</label>
			<div class="formControls col-xs-8 col-sm-10">
				@foreach($schedules as $key=>$value)
				<dl class="permission-list {{$value->channel}}" style="display: none;">
					<dt>
						<label>
							<input type="checkbox" value="{{$value->id}}" name="schedule_id" @if($value->id==$tournament->schedule_id) checked="checked"  @endif >
							{{$value->title}}</label> <label style="float: right;color: #999;">{{$value->start}}</label>
					</dt>
					<dd>
						<dl class="cl permission-list2">
							<dt>
								<label class="">
									<input type="checkbox" value="" id="">
									全选 &nbsp;&nbsp;&nbsp;&nbsp;<i class="Hui-iconfont">&#xe6c0;</i> </label>
							</dt>
							<dd>
								@foreach($value->actives as $k=>$v)
								<label style="float: left;">
									<input type="checkbox"  value="{{$v->name}}" @if(in_array($v->id,array_keys($tournament->actives))) checked="checked"  @endif name="active[{{$v->id}}]">
									{{$v->name}}
								</label>
								@endforeach
							</dd>
						</dl>
					</dd>
				</dl>
				@endforeach
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<button type="submit" class="btn btn-success radius" id="admin-role-save" name=""><i class="icon-ok"></i> 确定</button>
				<button onClick="removeIframe();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
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
		}
	});

	$("#form-manage-signup-add").validate({
		rules:{
			city_id:{
				required:true,
			},
			short_name:{
				required:true,
			}
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			$(form).ajaxSubmit();
			var index = parent.layer.getFrameIndex(window.name);
			parent.layer.close(index);
		}
	});

	var val = $('select[name="channel"]').val();
	$('.'+val ).css({display:'block'});
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