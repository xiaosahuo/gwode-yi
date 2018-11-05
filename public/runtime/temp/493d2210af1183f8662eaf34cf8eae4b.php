<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:92:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\channel\statistics.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\head.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\left.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\foot.html";i:1540978504;}*/ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>首页管理 | 得艺成</title>
    <style>
        .filepath{
            position: absolute;
            opacity: 0;
        }
        .content>.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            vertical-align: middle;
        }
        div.btn-group>button.btn{
            height: 35px;
        }
        .nav-header > li > a, .nav-header > li > .btn-group > a{
            padding: 10px 12px!important;
        }
        .pull-right>li:last-child>button{
            height: 35px;
        }
    </style>

    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">
    <link rel="stylesheet" href="http://t166670.cckanshu.com/public/static/libs/sweetalert/sweetalert.min.css">
    <link rel="stylesheet" href="http://t166670.cckanshu.com/public/static/libs/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="http://t166670.cckanshu.com/public/static/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://t166670.cckanshu.com/public/static/admin/css/oneui.css">
    <link rel="stylesheet" href="http://t166670.cckanshu.com/public/static/admin/css/ieasynet.css" id="css-main">
    <script src="http://t166670.cckanshu.com/public/static/admin/js/core/jquery.min.js"></script>
    <script src="http://t166670.cckanshu.com/public/static/admin/js/core/bootstrap.min.js"></script>
    <script src="http://t166670.cckanshu.com/public/static/libs/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript" src="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/js/bootstrap-select.js"></script>
    <link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/css/bootstrap-select.css">
    <script src="http://t166670.cckanshu.com/public/static/libs/webuploader/webuploader.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>
    <!--自定义css-->
    <link rel="stylesheet" href="http://t166670.cckanshu.com/public/static/admin/css/custom.css">
    <link href="https://cdn.bootcss.com/bootstrap-switch/3.3.4/css/bootstrap2/bootstrap-switch.min.css" rel="stylesheet">

</head>
<body>
<!-- Page Container -->
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed ">
    <!-- Side Overlay-->

    <aside id="side-overlay">
        <!-- Side Overlay Scroll Container -->
        <div id="side-overlay-scroll">
            <!-- Side Header -->
            <div class="side-header side-content">
                <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                <button class="btn btn-default pull-right" type="button" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-times"></i>
                </button>
                <span>
                    <img class="img-avatar img-avatar32" src="/public/static/admin/img/avatar.jpg" alt="">
                    <span class="font-w600 push-10-l">admin</span>
                </span>
            </div>
            <!-- END Side Header -->
            <!--侧栏-->
            <!-- Side Content -->
            <div class="side-content remove-padding-t" id="aside">
                <!-- Side Overlay Tabs -->
                <div class="block pull-r-l border-t">
                    <div class="block-content">
                        <div class="block pull-r-l">
                            <div class="block-header bg-gray-lighter">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">系统设置</h3>
                            </div>
                            <div class="block-content">
                                <div class="form-bordered">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <div class="font-s13 font-w600">站点开关</div>
                                                <div class="font-s13 font-w400 text-muted">站点关闭后将不能访问</div>
                                            </div>
                                            <div class="col-xs-4 text-right">
                                                <label class="css-input switch switch-sm switch-primary push-10-t">
                                                    <input type="checkbox" data-table="admin_config" data-id="1" data-field="value" checked=""><span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Side Overlay Tabs -->
            </div>
            <!-- END Side Content -->
        </div>
        <!-- END Side Overlay Scroll Container -->
    </aside>

    <!-- END Side Overlay -->

    <!-- Sidebar -->

    <nav id="sidebar">
        <!-- Sidebar Scroll Container -->
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 949px;"><div id="sidebar-scroll" style="overflow: hidden; width: auto; height: 949px;">
            <!-- Sidebar Content -->
            <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
            <div class="sidebar-content">
                <!-- Side Header -->
                <div class="side-header side-content bg-white-op ieasynet-header">
                    <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                    <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                    </button>
                    <!-- Themes functionality initialized in App() -> uiHandleTheme() -->
                    <div class="btn-group pull-right">
                        <button class="btn btn-link text-gray dropdown-toggle" data-toggle="dropdown" type="button">
                        </button>
                    </div>
                    <a class="h5 text-white" href="http://t166670.cckanshu.com/admin.php/admin/index/index.html">
                        <img src="http://t166670.cckanshu.com/public/static/admin/img/logo.png" class="logo" alt="Ieasynet PHP">
                        <img src="http://t166670.cckanshu.com/public/static/admin/img/logo-text.png" class="logo-text sidebar-mini-hide" alt="Ieasynet PHP">
                    </a>
                </div>



                <div class="side-content" id="sidebar-menu">

    <ul class="nav-main" id="side-item">
        <?php if(is_array($menu) || $menu instanceof \think\Collection): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if(empty($vo['listArea']) || ($vo['listArea'] instanceof \think\Collection && $vo['listArea']->isEmpty())): ?>
            <li>
                <a  href="/admin/<?php echo $vo['url']; ?>" target="_self"><span class="sidebar-mini-hide"><?php echo $vo['menu_name']; ?></span></a>
            </li>
        <?php else: ?>
            <li class="dropdown open">
                <a href="<?php echo $vo['url']; ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true"><?php echo $vo['menu_name']; ?> <span class="caret"></span></a>
                <ul class="dropdown">
                    <?php if(is_array($vo['listArea']) || $vo['listArea'] instanceof \think\Collection): $i = 0; $__LIST__ = $vo['listArea'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                    <li >
                        <a  href="/admin/<?php echo $v['url']; ?>" target="_self"><span class="sidebar-mini-hide"><?php echo $v['menu_name']; ?></span></a>
                    </li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </li>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>

    </ul>


</div>


                <!-- END Side Header -->

                <!-- Side Content -->
                <!-- END Side Content -->
            </div>
            <!-- Sidebar Content -->
        </div><div class="slimScrollBar" style="background: rgb(255, 255, 255); width: 5px; position: absolute; top: 0px; opacity: 0.35; display: none; border-radius: 7px; z-index: 99; right: 2px; height: 949px;"></div><div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 1; z-index: 90; right: 2px;"></div></div>
        <!-- END Sidebar Scroll Container -->
    </nav>

    <!-- END Sidebar -->

    <!-- Header -->

    <header id="header-navbar" class="content-mini content-mini-full">
        <!-- Header Navigation Right -->
        <ul class="nav-header pull-right">
        <!--<li>-->
        <!--<div class="btn-group">-->
        <!--<button class="btn btn-default btn-image dropdown-toggle" data-toggle="dropdown" type="button">-->
        <!--<img src="/public/static/admin/img/avatar.jpg" alt="admin">-->
        <!--<span class="caret"></span>-->
        <!--</button>-->
        <!--<ul class="dropdown-menu dropdown-menu-right">-->
        <!--<li class="dropdown-header">admin (超级管理员)</li>-->
        <!--<li>-->
        <!--<a tabindex="-1" href="/admin.php/admin/index/profile.html">-->
        <!--<i class="si si-settings pull-right"></i>个人设置-->
        <!--</a>-->
        <!--</li>-->
        <!--<li class="divider"></li>-->
            <li>
                <a tabindex="-1" href="<?php echo url('Simple/loginOut'); ?>">
                    退出帐号
                </a>
            </li>
        </ul>
        <!--</div>-->
        <!--</li>-->
        <!---->
        <!--</ul>-->
        <!-- END Header Navigation Right -->
        <!-- Header Navigation Left -->
    </header>
<main id="main-container" style="min-height: 912px;">
    <ol class="breadcrumb">
        <a class="link-effect">支付统计</a>
    </ol>
    <div class='content'>
        <!--<div class="container">-->
        <form action="<?php echo url('Channel/statistics'); ?>" method="get">
            <div class="col-xs-3">
                <select  class="selectpicker show-tick form-control" name='channelid' id="channelid"  data-live-search="true">
                    <option value="0">请选择渠道</option>
                    <?php if(is_array($channellist) || $channellist instanceof \think\Collection): $i = 0; $__LIST__ = $channellist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == $channelid): ?> selected <?php endif; ?>><?php echo $v['channel']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <button type="submit"  class="btn btn-primary">查询</button>
        </form>
       <!-- </div>-->
        <div class="block-content tab-content">
            <div class="col-md-3">
                <div class="well">
                    <b>今日充值</b>
                    <div class="text-primary" style="font-size:32px;margin:5px 0">
                        ¥<span data-bind="price: stats_today.paid_amount"><?php echo $stats['today']['total_count']; ?> 元 </span>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6" style="padding:0">
                                <strong>普通充值</strong>
                                <div><b class="text-primary" data-bind="price: stats_today.welth_order_paid_amount"><?php echo $stats['today']['welth_total']; ?> 元 </b></div>
                                <div>已支付: <b class="text-warning" data-bind="text: stats_today.welth_order_paid_count"><?php echo $stats['today']['welth_order_pay_amount']; ?></b> 笔</div>
                                <div>未支付: <b class="text-warning" data-bind="text: stats_today.welth_order_unpaid_count"><?php echo $stats['today']['welth_order_unpay_amount']; ?></b> 笔</div>
                            </div>
                            <div class="col-sm-6" style="padding:0">
                                <strong>年费VIP会员</strong>
                                <div><b class="text-primary" data-bind="price: stats_today.vip_order_paid_amount"><?php echo $stats['today']['vip_total']; ?> 元 </b></div>
                                <div>已支付: <b class="text-warning" data-bind="text: stats_today.vip_order_paid_count"><?php echo $stats['today']['vip_order_pay_amount']; ?></b> 笔</div>
                                <div>未支付: <b class="text-warning" data-bind="text: stats_today.vip_order_unpaid_count"><?php echo $stats['today']['vip_order_unpay_amount']; ?></b> 笔</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well">
                    <b>昨日充值 </b>
                    <div class="text-primary" style="font-size:32px;margin:5px 0">
                        ¥<span data-bind="price: stats_yesterday.paid_amount"><?php echo $stats['yesterday']['total_count']; ?> 元 </span>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6" style="padding:0">
                                <strong>普通充值</strong>
                                <div><b class="text-primary" data-bind="price: stats_yesterday.welth_order_paid_amount"><?php echo $stats['yesterday']['welth_total']; ?> 元 </b></div>
                                <div>已支付: <b class="text-warning" data-bind="text: stats_yesterday.welth_order_paid_count"><?php echo $stats['yesterday']['welth_order_pay_amount']; ?></b> 笔</div>
                                <div>未支付: <b class="text-warning" data-bind="text: stats_yesterday.welth_order_unpaid_count"><?php echo $stats['yesterday']['welth_order_unpay_amount']; ?></b> 笔</div>

                            </div>
                            <div class="col-sm-6" style="padding:0">
                                <strong>年费VIP会员</strong>
                                <div><b class="text-primary" data-bind="price: stats_yesterday.vip_order_paid_amount"><?php echo $stats['yesterday']['vip_total']; ?> 元 </b></div>
                                <div>已支付: <b class="text-warning" data-bind="text: stats_yesterday.vip_order_paid_count"><?php echo $stats['yesterday']['vip_order_pay_amount']; ?></b> 笔</div>
                                <div>未支付: <b class="text-warning" data-bind="text: stats_yesterday.vip_order_unpaid_count"><?php echo $stats['yesterday']['vip_order_unpay_amount']; ?></b> 笔</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well">
                    <b>本月充值 </b>
                    <div class="text-primary" style="font-size:32px;margin:5px 0">
                        ¥<span data-bind="price: stats_this_month.paid_amount"><?php echo $stats['month']['total_count']; ?> 元 </span>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6" style="padding:0">
                                <strong>普通充值</strong>
                                <div><b class="text-primary" data-bind="price: stats_this_month.welth_order_paid_amount"><?php echo $stats['month']['welth_total']; ?> 元 </b></div>
                                <div>已支付: <b class="text-warning" data-bind="text: stats_this_month.welth_order_paid_count"><?php echo $stats['month']['welth_order_pay_amount']; ?></b> 笔</div>
                                <div>未支付: <b class="text-warning" data-bind="text: stats_this_month.welth_order_unpaid_count"><?php echo $stats['month']['welth_order_unpay_amount']; ?></b> 笔</div>
                            </div>
                            <div class="col-sm-6" style="padding:0">
                                <strong>年费VIP会员</strong>
                                <div><b class="text-primary" data-bind="price: stats_this_month.vip_order_paid_amount"><?php echo $stats['month']['vip_total']; ?> 元 </b></div>
                                <div>已支付: <b class="text-warning" data-bind="text: stats_this_month.vip_order_paid_count"><?php echo $stats['month']['vip_order_pay_amount']; ?></b> 笔</div>
                                <div>未支付: <b class="text-warning" data-bind="text: stats_this_month.vip_order_unpaid_count"><?php echo $stats['month']['vip_order_unpay_amount']; ?></b> 笔</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well">
                    <b>累计充值 </b>
                    <div class="text-primary" style="font-size:32px;margin:5px 0">
                        ¥<span data-bind="price: stats_all_time.paid_amount"><?php echo $stats['total']['total_count']; ?> 元 </span>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6" style="padding:0">
                                <strong>普通充值</strong>
                                <div><b class="text-primary" data-bind="price: stats_all_time.welth_order_paid_amount"><?php echo $stats['total']['welth_total']; ?> 元 </b></div>
                                <div>已支付: <b class="text-warning" data-bind="text: stats_all_time.welth_order_paid_count"><?php echo $stats['total']['welth_order_pay_amount']; ?></b> 笔</div>
                                <div>未支付: <b class="text-warning" data-bind="text: stats_all_time.welth_order_unpaid_count"><?php echo $stats['total']['welth_order_unpay_amount']; ?></b> 笔</div>
                            </div>
                            <div class="col-sm-6" style="padding:0">
                                <strong>年费VIP会员</strong>
                                <div><b class="text-primary" data-bind="price: stats_all_time.vip_order_paid_amount"><?php echo $stats['total']['vip_total']; ?> 元 </b></div>
                                <div>已支付: <b class="text-warning" data-bind="text: stats_all_time.vip_order_paid_count"><?php echo $stats['total']['vip_order_pay_amount']; ?></b> 笔</div>
                                <div>未支付: <b class="text-warning" data-bind="text: stats_all_time.vip_order_unpaid_count"><?php echo $stats['total']['vip_order_unpay_amount']; ?></b> 笔</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="tab-pane active">
                <div class="row module-list push-20">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-builder table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>渠道别名</th>
                                    <th>渠道地址</th>
                                    <th>渠道首页打开章节</th>
                                    <th>已充总金额</th>
                                    <th>未充总金额</th>
                                    <th>已充值笔数</th>
                                    <th>未充值笔数</th>
                                    <th class="column-right_button">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($channel_list) || $channel_list instanceof \think\Collection): $i = 0; $__LIST__ = $channel_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td><?php if($vo['channel_name'] == ''): ?> 未知 <?php else: ?> <?php echo $vo['channel_name']; endif; ?> </td>
                                        <td><?php if($vo['channel_address'] == ''): ?> 暂无 <?php else: ?> <?php echo $vo['channel_address']; endif; ?> </td>
                                        <td><?php echo $vo['btitle']; ?>-<?php echo $vo['chtitle']; ?> </td>
                                        <td><?php echo $vo['RechargeMoney']; ?> 元 </td>
                                        <td><?php echo $vo['NoRechargeMoney']; ?> 元 </td>
                                        <td><?php echo $vo['RechargeNumber']; ?> 笔 </td>
                                        <td><?php echo $vo['NoRechargeNumber']; ?> 笔 </td>
                                        <td> <a href="<?php echo url('Channel/statisticeDetail',['channel_id' => $vo['channel_id']]); ?>"> 查看 </a> </td>
                                    </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="data-table-toolbar">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo $page; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
</body>
</html>
