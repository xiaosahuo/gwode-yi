<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\index\upgrade.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\head.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\left.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\foot.html";i:1540978504;}*/ ?>
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
            <a class="link-effect">升级管理</a>
        </ol>

        <div class='content'>
            <div class="toolbar-btn-action">
                <a title="新增" icon="fa fa-plus-circle" class="btn btn-primary" href="<?php echo url('index/upgradeAdd'); ?>">
                    +新增</a>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <td class="col-xs-1">版本名称</td>
                <td class="col-xs-1">更新地址</td>
                <td class="col-xs-1">渠道</td>
                <td class="col-xs-2">更新内容</td>
                <td class="col-xs-1">强制状态</td>
                <td class="col-xs-1">是否强制</td>
                <td class="col-xs-1">状态</td>
                <td class="col-xs-1">版本号</td>
                <td class="col-xs-2">操作</td>
                </thead>
                <tbody>
                <!--本期精选-->
                <?php if(is_array($res) || $res instanceof \think\Collection): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td>
                        <input type="hidden" name='id' value="<?php echo $vo['id']; ?>" >
                        <?php echo $vo['version']; ?>
                        <!--<input type="type"  name='version' value="<?php echo $vo['version']; ?>" >-->
                    </td>
                    <td>
                        <?php echo $vo['address']; ?>
                        <!--<input type="type"  name='address' value="<?php echo $vo['address']; ?>" >-->
                    </td>
                    <td><?php echo $vo['channel']; ?></td>
                    <td>
                        <?php echo $vo['content']; ?>
                        <!--<textarea name="content" id="" cols="30" rows="5"><?php echo $vo['content']; ?></textarea>-->
                        <!--<input type="type"  name='content' value="<?php echo $vo['content']; ?>">-->
                    </td>
                    <td>
                        <span class="common-button ajax-status">
                            <?php switch($vo['renew']): case "0": ?><span class="sta_er">不强制</span><?php break; case "1": ?><span class="sta_bl">强制</span><?php break; endswitch; ?>
                        </span>
                    </td>
                    <td>
                        <?php switch($vo['renew']): case "0": ?>  <a href="javascript:;" class="data-lock "  onClick="aa(<?php echo $vo['id']; ?>)" >是</a><?php break; case "1": ?><a href="javascript:;" class="data-activation"  onClick="aa(<?php echo $vo['id']; ?>)">否</a><?php break; endswitch; ?>

                    </td>

                    <td><?php if($vo['status'] == 1): ?> <a href="javascript:void(0);" onclick="changeDown(<?php echo $vo['id']; ?>)">已使用</a> <?php else: ?> <a href="javascript:void(0);" onclick="changeUp(<?php echo $vo['id']; ?>)">已停用</a> <?php endif; ?> </td>

                    <td style="width: 125px">
                        <span ><?php echo $vo['versionCode']; ?></span>
                        <!--<input type="type"  name='versionCode' value="<?php echo $vo['versionCode']; ?>" >-->
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
            <div class="data-table-toolbar">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $page; ?>
                    </div>
                </div>
            </div>
        </div>
        <!--页面js-->
        <script>
            //追加按钮
            $('tbody tr').append(`<td>
            <button class="button button-caution button-jumbo">删除</button>
        </td>`);
            //找出选中的select选项----用于提交
            /*$('.click').click(function(){
                // 小说位置的id
                var id=$(this).parents('tr').children('td').eq(0).children('input').val();
                var address=$(this).parents('tr').children('td').eq(1).children('input').val();
                var content=$(this).parents('tr').children('td').eq(2).children('textarea').val();
                var version=$(this).parents('tr').children('td').eq(0).children().eq(1).val();
                $.ajax({
                    url: '/admin/index/upgradeAlter',
                    data: {"address":address,"id":id,"content":content,"version":version},
                    type: 'POST',
                    error: function (json) {
                        alert('系统屏蔽！');
                    },
                    success: function (json) {
                        if(json){
                            window.location.reload();
                        }
                    }
                })
            });*/
            $('.button').click(function(){
                // 小说位置的id
                var id=$(this).parents('tr').children('td').eq(0).children('input').val();
                $.ajax({
                    url: '/admin/index/upgradeDelete',
                    data: {"id":id},
                    type: 'POST',
                    error: function (json) {
                        alert('系统屏蔽！');
                    },
                    success: function (json) {
                        if(json){
                            window.location.reload();
                        }
                    }
                })
            });
            <!--强制更新设置-->
            function aa(id) {
                $.ajax({
                    type: 'post',
                    url: '/admin/index/force',
                    data: {id: id},
                    dataType: 'json',
                    success: function (re) {
                        location.reload()
                        if(re==1){
                            location.reload()
                        }

                    }

                });

            }
        </script>

        <script>
            var url = "<?php echo url('Index/upgradeStatus'); ?>";
            function changeUp(id)
            {
                var data = {'id':id,'status':1};
                $.post(url,data,function(res){
                    location.reload();
                });
            }

            function changeDown(id)
            {
                var data = {'id':id,'status':0};
                $.post(url,data,function(res){
                    location.reload();
                });
            }


        </script>
        </main>
</div>
</body>
</html>
