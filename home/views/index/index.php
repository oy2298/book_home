<?php
use app\widgets\MainWidget;
?>
<div class="loader" style="display:none">
    <div class="la-ball-clip-rotate">
        <div></div>
    </div>
</div>

<div data-skin="default" class="skin-default ">

<div class="head">
<nav class="navbar navbar-default" role="navigation">
        <div class="container ">
                <div class="navbar-header">
                <a class="navbar-brand" href="http://test.gxmuzi.com/">
                <img src="./publics/resource/images/logo/logo.png" class="pull-left" width="110px" height="35px">
                <span class="version hidden">1.0.0</span>
                </a>
                </div>
                <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-left">
                        <?=MainWidget::mainHead();?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown msg">
                                        <a href="javascript:;" class="dropdown-toogle" data-toggle="dropdown"><span class="wi wi-bell"></span>消息</a>
                                        <div class="dropdown-menu">
                                            <div class="clearfix top">消息<a href="./index.php?c=message&a=notice&" class="pull-right">查看更多</a></div>
                                            <div class="msg-list-container">
                                                <div class="msg-list">
                                                </div>
                                            </div>
                                        </div>
                                </li>
                                
                                <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="wi wi-user color-gray"></i>gxmuzi <span class="caret"></span></a>
                                        <ul class="dropdown-menu color-gray" role="menu">
                                            <li><a href="./index.php?c=user&a=profile&" target="_blank"><i class="wi wi-account color-gray"></i> 我的账号</a></li>
                                            <li class="divider"></li>
                                            <li><a href="./index.php?c=cloud&a=upgrade&" target="_blank"><i class="wi wi-update color-gray"></i> 自动更新</a></li>											
                                            <li><a href="./index.php?c=system&a=updatecache&" target="_blank"><i class="wi wi-cache color-gray"></i> 更新缓存</a></li>
                                            <li class="divider"></li>
                                            <li><a href="./index.php?c=user&a=logout&"><i class="fa fa-sign-out color-gray"></i> 退出系统</a></li>
                                        </ul>
                                </li>
                        </ul>
                </div>
        </div>
</nav>
</div>

 
<div class="main">
<div class="container">
<a href="javascript:;" class="js-big-main button-to-big color-gray" title="加宽">宽屏</a>
<div class="panel panel-content main-panel-content ">
			<div class="content-head panel-heading main-panel-heading"><span class="font-lg"><i class="wi wi-setting"></i> 系统管理</span></div>
<div class="panel-body clearfix main-panel-body ">


            <div class="left-menu">
                    <div class="left-menu-content">
                                <?=MainWidget::mainLeft();?>
                    </div>
            </div>

            <div class="right-content">
                        <!--系统管理首页-->
                    <div class="welcome-container js-system-welcome">
                        <div class="ad-img we7-margin-bottom">
                            <a ng-href="{{ad.url}}" target="_blank" ng-repeat="ad in ads"><img ng-src="{{ad.src}}" alt="" class="img-responsive" style="margin: 0 auto;"></a>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel we7-panel account-stat">
                                    <div class="panel-heading">微信应用模块</div>
                                    <div class="panel-body we7-padding-vertical">
                                        <div class="col-sm-4 text-center">
                                            <div class="title">未安装应用</div>
                                            <div class="num">
                                                <a href="./index.php?c=module&a=manage-system&do=not_installed&account_type=1" class="color-default">{JS模板调用}</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="title">可升级应用</div>
                                            <div class="num">
                                            {JS模板调用}
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="title">应用总数</div>
                                            <div class="num">
                                                <a href="./index.php?c=module&a=manage-system&do=installed&account_type=1" class="color-default">{JS模板调用}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel we7-panel account-stat">
                                    <div class="panel-heading">小程序应用模块</div>
                                    <div class="panel-body we7-padding-vertical">
                                        <div class="col-sm-4 text-center">
                                            <div class="title">未安装应用</div>
                                            <div class="num">
                                                <a href="./index.php?c=module&a=manage-system&do=not_installed&account_type=4" class="color-default">{JS模板调用}</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="title">可升级应用</div>
                                            <div class="num">
                                            {JS模板调用}
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="title">应用总数</div>
                                            <div class="num">
                                                <a href="./index.php?c=module&a=manage-system&do=installed&account_type=4" class="color-default">{JS模板调用}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="modal-loading" style="width:100%; display:none">
                                <div style="text-align:center;background-color: transparent;">
                                    <img style="width:48px; height:48px; margin-top:10px;margin-bottom:10px;" src="./publics/resource/images/loading.gif" title="正在努力加载...">
                                </div>
                            </div>
                        </div>
                        <div class="panel we7-panel system-update" ng-if="upgrade_show == 1">
                            <div class="panel-heading">
                                <span class="color-gray pull-right">当前版本：v1.6.4（201712020001）</span>
                                系统更新
                            </div>
                            <div class="panel-body we7-padding-vertical clearfix">
                                <div class="col-sm-3 text-center">
                                    <div class="title">更新文件</div>
                                    <div class="num">{JS模板调用}个</div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <div class="title">更新数据库</div>
                                    <div class="num">{JS模板调用} 项</div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <div class="title">更新脚本</div>
                                    <div class="num">{JS模板调用}项</div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <a href="./index.php?c=cloud&a=upgrade&" class="btn btn-danger">去更新</a>
                                </div>
                            </div>
                        </div>
                        <div class="panel we7-panel database">
                            <div class="panel-heading">
                                数据库备份提醒
                            </div>
                            <div class="panel-body clearfix">
                                <div class="col-sm-9">
                                    <span class="day">0</span>
                                    <span class="color-default">天</span>
                                    没有备份数据库了,请及时备份!
                                </div>
                                <div class="col-sm-3 text-center">
                                    <a class="btn btn-default" href="./index.php?c=system&a=database&">开始备份</a>
                                </div>
                            </div>
                        </div>
                        <div class="panel we7-panel apply-list">
                            <div class="panel-heading">
                                <span class="pull-right">
                                    <a href="./index.php?c=module&a=manage-system&account_type=1" class="color-default">查看更多公众号应用</a>
                                    <span class="we7-padding-horizontal inline-block color-gray">|</span>
                                    <a href="./index.php?c=module&a=manage-system&account_type=4" class="color-default">查看更多小程序应用</a>
                                </span>
                                可升级应用
                            </div>
                            <div class="panel-body">
                                <a href="{{module.link}}" target="_blank" class="apply-item" ng-repeat="module in upgrade_module_list">
                                    <img src="{{module.logo}}" class="apply-img"/>
                                    <span class="text-over">{JS模板调用}</span>
                                    <span class="color-red">升级</span>
                                </a>
                                <div class="text-center" ng-if="upgrade_modules_show == 0">
                                    没有可升级的应用
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end 系统管理首页-->
            </div>
            
</div>
</div>
</div>
</div>


<?=MainWidget::mainFoot();?>

