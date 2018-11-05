<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\index\dif.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\head.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\left.html";i:1540978504;s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/admin\view\public\foot.html";i:1540978504;}*/ ?>
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
            <a class="link-effect">男女书籍管理</a>
        </ol>
        <div class='content'>
            <table class="table table-striped table-hover">
                <thead>
                <td class="col-xs-2">小说位置</td>
                <td class="col-xs-5">小说名字</td>
                <td class="col-xs-2">操作</td>
                </thead>
                <tbody>
                <!--本期精选-->
                <?php if(is_array($res) || $res instanceof \think\Collection): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php if($vo['wz'] == 'ns1'): ?> 男生默认书籍-1
                        <?php elseif($vo['wz'] == 'ns2'): ?>  男生默认书籍-2
                        <?php elseif($vo['wz'] == 'ns3'): ?>  男生默认书籍-3
                        <?php elseif($vo['wz'] == 'ns4'): ?>  男生默认书籍-4
                        <?php elseif($vo['wz'] == 'ns5'): ?>  男生默认书籍-5
                        <?php elseif($vo['wz'] == 'vs1'): ?>  女生默认书籍-1
                        <?php elseif($vo['wz'] == 'vs2'): ?>  女生默认书籍-2
                        <?php elseif($vo['wz'] == 'vs3'): ?>  女生默认书籍-3
                        <?php elseif($vo['wz'] == 'vs4'): ?>  女生默认书籍-4
                        <?php elseif($vo['wz'] == 'vs5'): ?>  女生默认书籍-5
                        <?php else: ?> 全体 <?php endif; ?>
                        <input type="hidden" name='wz' value="<?php echo $vo['wz']; ?>" ></td>
                    <td>
                        <div class="col-xs-5">
                            <select  class="selectpicker show-tick form-control" name='bookid'  data-live-search="true">
                                <?php if($vo['status'] == 0): ?>
                                <option value="0"> 请选择 </option>
                                <?php endif; if(is_array($book) || $book instanceof \think\Collection): $i = 0; $__LIST__ = $book;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bvo): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo $bvo['id']; ?>" <?php if($bvo['id'] == $vo['bookid']): ?> selected = "selected" <?php endif; ?>><?php echo $bvo['title']; if($bvo['id'] == $vo['bookid']): ?> --当前选择<?php endif; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
        <!--页面js-->
        <script>
            //追加按钮
            $('tbody tr').append(`<td>
            <button class="click btn btn-success btn-large">提交</button>
        </td>`);
            //filereader 的方法
            function changepic() {
                var reads= new FileReader();
                f=document.getElementById('file').files[0];
                reads.readAsDataURL(f);
                reads.onload=function (e) {
                    document.getElementById('show').src=this.result;
                };
            }
            function changepic1() {
                var reads= new FileReader();
                f=document.getElementById('file1').files[0];
                reads.readAsDataURL(f);
                reads.onload=function (e) {
                    document.getElementById('show1').src=this.result;
                };
            }
            function changepic2() {
                var reads= new FileReader();
                f=document.getElementById('file2').files[0];
                reads.readAsDataURL(f);
                reads.onload=function (e) {
                    document.getElementById('show2').src=this.result;
                };
            }
            //找出选中的select选项----用于提交
            $('.click').click(function(){
                // 小说位置的id
                var id=$(this).parents('tr').children('td').eq(0).children('input').val();
                var trIndex=$(this).parents('tr').index();
                var value=$('li.selected').eq(trIndex).attr('data-original-index');
                var bid=$('li.selected').eq(trIndex).parents('div.col-xs-5').children('select').children().eq(value).val();
                $.ajax({
                    url: '/admin/index/res',// /wz/'+id+'/bookid/'+bid,
                    data: {"bookid":bid,"seat":id},
                    type: 'POST',
                    error: function (json) {
                        alert('系统屏蔽！');
                    },
                    success: function (json) {
                        //console.log(json);
                        if(json){
                            window.location.reload();
                        }
                    }
                })
            });
        </script>
        </main>
</div>
</body>
</html>
