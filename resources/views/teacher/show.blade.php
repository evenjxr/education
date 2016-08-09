@extends('layout2')
@section('body')
<div class="cl pd-20" style=" background-color:#5bacb6">
    <img class="avatar size-XL l" src="{{$user->avatar}}">
    <dl style="margin-left:80px; color:#fff">
        <dt><span class="f-18">@if($user->name){{$user->name}} @else {{$user->nickname}} @endif</span> <span class="pl-10 f-12">年龄：{{$user->age}}</span></dt>
        <dd class="pt-10 f-12" style="margin-left:0">身高: {{$user->height}} cm   体重: {{$user->weight}} kg</dd>
    </dl>
</div>
<div class="pd-20">
    <table class="table">
        <tbody>
        <tr>
            <th class="text-r" width="80">性别：</th>
            <td>
                @if($user->sex==1)
                    男
                @else
                    女
                @endif
            </td>
        </tr>
        <tr>
            <th class="text-r">手机：</th>
            <td>{{$user->mobile}}</td>
        </tr>
        <tr>
            <th class="text-r">年龄：</th>
            <td>{{$user->age}}</td>
        </tr>
        <tr>
            <th class="text-r">城市：</th>
            <td>{{$user->city}}</td>
        </tr>
        <tr>
            <th class="text-r">注册时间：</th>
            <td>{{$user->created_at}}</td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript" src="static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="static/h-ui/js/H-ui.admin.js"></script>
@endsection
