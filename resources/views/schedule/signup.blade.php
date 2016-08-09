@extends('layout2')
@section('body')
<article class="page-container">
	<form method="get" action="{{ URL::route('manage.schedule.signup') }}">
		<div class="text-c">
			赛程日期
			<input type="text" name="start" onfocus="WdatePicker()" id="logmin" class="input-text Wdate" style="width:120px;">
			-
			<input type="text" name="title" id="" placeholder="搜赛程" style="width:250px" class="input-text">
			<button id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜赛程</button>
		</div>
	</form>

	<form action="{{ URL::route('manage.schedule.dosignup') }}" method="post" class="form form-horizontal" id="form-manage-signup-add">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>比赛类型：</label>
			<div class="formControls col-xs-8 col-sm-10">
				<span class="select-box">
				<select name="channel" class="select">
					<option value="personal" selected="ture">个人赛</option>
					<option value="team">团队赛</option>
					{{--<option value="other">其他</option>--}}
				</select>
				</span>
			</div>
		</div>
		<div class="row cl team">
			<label class="form-label col-xs-4 col-sm-2">
				<button class="btn btn-success" onClick="add_team('用户团队','{{ URL::route('manage.team.search')}}','900','500')" type="button"><i class="Hui-iconfont">&#xe665;</i> 选择团队</button>
			</label>
			<div class="formControls col-xs-8 col-sm-10">
				<input type="hidden" value="" placeholder="" select="team_id" name="team_id">
				<input type="text" style="width: 300px;" class="input-text" value="" placeholder="" select="team_name" name="team_name" datatype="*4-16" nullmsg="用户不得为空" readonly>
			</div>
		</div>

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
				@foreach($schedules as $key=>$value)
				<dl class="permission-list {{$value->channel}}">
					<dt>
						<label>
							<input type="checkbox" value="{{$value->id}}" name="schedule_id" id="user-Character-0">
							{{$value->title}}  {{$value->sign_num}}/{{$value->max_num}} </label> <label style="float: right;color: #999;">{{$value->start}}</label>
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
									<input type="checkbox"  value="{{$v->name}}" name="active[{{$v->id}}]">
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
			<div class="col-xs-8 col-sm-2 col-xs-offset-4 col-sm-offset-3">
				<button type="submit" class="btn btn-success radius" id="admin-role-save" name=""><i class="icon-ok"></i> 确定</button>
				<button onClick="layer_close();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
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