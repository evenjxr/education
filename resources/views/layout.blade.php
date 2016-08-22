<!doctype html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/static/h-ui/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="/static/h-ui/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/static/h-ui/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>场上见后台管理系统</title>
<meta name="keywords" content="">
<meta name="description" content="">
    @yield('css')
</head>
<body>
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="{{ URL::route('manage.admin.index')}}" style="color: transparent;"></a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="">H-ui</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
            <nav class="nav navbar-nav">
                <ul class="cl">
                     <li class="dropDown dropDown_hover"><a href="javascript:;" class="dropDown_A"><i class="Hui-iconfont">&#xe600;</i> 新增 <i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:;" onclick="article_add()"><i class="Hui-iconfont">&#xe616;</i> 新闻</a></li>
                            <li><a href="javascript:;" onclick="picture_add(')"><i class="Hui-iconfont">&#xe613;</i> 图片</a></li>
                            <li><a href="javascript:;" onclick="product_add()"><i class="Hui-iconfont">&#xe620;</i> 活动</a></li>
                            <li><a href="javascript:;" onclick="member_add()"><i class="Hui-iconfont">&#xe60d;</i> 用户</a></li>
                        </ul>
                    </li> 
                </ul>
            </nav>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li>超级管理员</li>
                    <li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A">{{ Session::get('admin.name') }}<i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            {{--<li><a _href="{{ URL::route('manage.admin.detail',['id'=>Session::get('admin.id')]) }}" >个人信息</a></li>--}}
                            <li><a href="{{ URL::route('manage.login.out') }}">切换账户</a></li>
                            <li><a href="{{ URL::route('manage.login.out') }}">退出</a></li>
                        </ul>
                    </li>
                    <!-- <li id="Hui-msg"> <a href="#" title="消息"><span class="badge badge-danger">1</span><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li> -->
                    <li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
                            <li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
                            <li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
                            <li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
                            <li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
                            <li><a href="javascript:;" data-val="orange" title="绿色">橙色</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<aside class="Hui-aside">
    <input runat="server" id="divScrollValue" type="hidden" value="" />
    <div class="menu_dropdown bk_2">

        <dl id="menu-article">
            <dt><i class="Hui-iconfont">&#xe616;</i> 新闻管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a _href="{{ URL::route('manage.news.lists') }}" data-title="新闻列表" href="javascript:void(0)">新闻列表</a></li>
                     <li><a _href="{{ URL::route('manage.news.add') }}" data-title="新增新闻" href="javascript:void(0)">新增新闻</a></li>
                </ul>
            </dd>
        </dl>


        <dl id="menu-student">
            <dt><i class="Hui-iconfont">&#xe60d;</i> 学员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a _href="{{ URL::route('manage.student.lists') }}" data-title="学员列表" href="javascript:;">学员列表</a></li>
                    <li><a _href="{{ URL::route('manage.student.add') }}" data-title="新增学员" href="javascript:;">新增学员</a></li>
                </ul>
            </dd>
        </dl>
        <dl id="menu-teacher">
            <dt><i class="Hui-iconfont">&#xe60d;</i> 教师管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a _href="{{ URL::route('manage.teacher.lists') }}" data-title="教师列表" href="javascript:;">教师列表</a></li>
                    <li><a _href="{{ URL::route('manage.teacher.add') }}" data-title="新增教师" href="javascript:;">新增教师</a></li>
                </ul>
            </dd>
        </dl>
        <dl id="menu-institution">
            <dt><i class="Hui-iconfont">&#xe60d;</i> 机构管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a _href="{{ URL::route('manage.institution.lists') }}" data-title="机构列表" href="javascript:;">机构列表</a></li>
                    <li><a _href="{{ URL::route('manage.institution.add') }}" data-title="新增机构" href="javascript:;">新增机构</a></li>
                </ul>
            </dd>
        </dl>
        <dl id="menu-institution">
            <dt><i class="Hui-iconfont">&#xe60d;</i> 订单管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a _href="{{ URL::route('manage.equipment.lists') }}" data-title="硬件安装订单" href="javascript:;">硬件安装订单</a></li>
                    <li><a _href="{{ URL::route('manage.server.lists') }}" data-title="服务预约订单" href="javascript:;">服务预约订单</a></li>
                </ul>
            </dd>
        </dl>
        <dl id="menu-admin">
            <dt><i class="Hui-iconfont">&#xe62d;</i> 管理员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    @if( Session::get('admin.role')=='admin' )
                    <li><a _href="{{ URL::route('manage.role.lists') }}" data-title="角色管理" href="javascript:void(0)">角色管理</a></li>
                    <li><a _href="{{ URL::route('manage.moudel.lists') }}" data-title="模块管理" href="javascript:void(0)">模块管理</a></li>
                    <li><a _href="{{ URL::route('manage.city.lists') }}" data-title="城市列表" href="javascript:void(0)">城市列表</a></li>
                    @endif
                    <li><a _href="{{ URL::route('manage.admin.lists') }}" data-title="管理员列表" href="javascript:void(0)">管理员列表</a></li>

                </ul>
            </dd>
        </dl>
        {{--@if(in_array('System',$moudel_name))--}}
        {{--<dl id="menu-system">--}}
            {{--<dt><i class="Hui-iconfont">&#xe62e;</i> 系统管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>--}}
            {{--<dd>--}}
                {{--<ul>--}}
                    {{--<li><a _href="system-base.html" data-title="系统设置" href="javascript:void(0)">系统设置</a></li>--}}
                    {{--<li><a _href="system-category.html" data-title="栏目管理" href="javascript:void(0)">栏目管理</a></li>--}}
                    {{--<li><a _href="system-data.html" data-title="数据字典" href="javascript:void(0)">数据字典</a></li>--}}
                    {{--<li><a _href="system-shielding.html" data-title="屏蔽词" href="javascript:void(0)">屏蔽词</a></li>--}}
                    {{--<li><a _href="system-log.html" data-title="系统日志" href="javascript:void(0)">系统日志</a></li>--}}
                {{--</ul>--}}
            {{--</dd>--}}
        {{--</dl>--}}
        {{--@endif--}}
    </div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>


<section class="Hui-article-box">
    @yield('section')
</section>


</body>
<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="/static/h-ui/js/H-ui.admin.js"></script> 
@yield('js')
</html>