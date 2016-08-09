@extends('layout2')
@section('css')
<link href="/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css" >
@endsection
@section('body')
<div class="page-container">
	<div class="portfolio-content">
		<ul class="cl portfolio-area">
			<form method="post" action='{{ URL::route("manage.library.picdelete") }}' id="delete">
				@foreach($pictures as $val )
				<li class="item">
					<div class="portfoliobox">
						<input class="checkbox" type="radio" name="url" value="{{$val->url}}">
						<div class="picbox ">
							<a href="{{$val->url}}" data-lightbox="gallery" data-title="{{$val->name}}">
								<img src="{{$val->url}}"></a>
						</div>
						<div class="textbox">{{$val->group_name}} </div>
					</div>
				</li>
				@endforeach
			</form>
		</ul>
	</div>
	<div style="margin-top: 30px; float: right;">
		<button id="" class="btn btn-success" type="submit" onclick="get_pic()">确定</button>
	</div>
</div>
<script type="text/javascript" src="/lib/lightbox2/2.8.1/js/lightbox-plus-jquery.min.js"></script> 
<script type="text/javascript">
$(function(){
	$.Huihover(".portfolio-area li");
});
function get_pic()
{
	var thumbnail = $(window.parent.parent.document).contents().find("#thumbnail");
	var img = $(window.parent.parent.document).contents().find("#img");
	var url = $('input[name="url"]:checked').val();
	$(thumbnail).val(url);
	$(img).css({width:'400px', height:'225px',margin: '5px',border:'1px solid #ccc'});
	$(img).attr('src',url);
	layer_close();
}

</script>
@endsection