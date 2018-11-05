<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/index\view\user\shelf.html";i:1541230020;}*/ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="telephone=no,email=no" name="format-detection">
    <script src="https://s.zhulang.com/wap/v2/js/lib/flexible.0.3.4.js"></script>
    <link href="https://s.zhulang.com/images/zl-logo114.png" rel="apple-touch-icon-precomposed">
    <title>我的书架-逐浪网</title>
    <meta name="keywords" content="逐浪">
    <meta name="description" content="登录-逐浪手机小说网">
    <link rel="stylesheet" href="https://s.zhulang.com/wap/pub/v2/style/style-71b392138c.css" type="text/css">
    <script>
        var userInfo = {
            uid : "65300450",
            nick : "小海豚_65300450"
        };
        var bookInfo = {
            bid : "",
            bookname : ""
        };
    </script>
</head>
<body ontouchstart >
<div class="wrap">
    <header class="pg-hd wh-hd">
        <a href="javascript:void(0)" class="icon fl backicon"><i class="iconfont">&#xe606;</i></a>
        <h2>书架</h2>
        <a href="/index/user/center.html" class="icon fr"><i class="iconfont">&#xe600;</i></a>
    </header>



    <div class="shelf-tab">
        <ul class="tab-grp-s">
            <li class="cur">我的书架</li>
            <li><a href="/index/user/zj">最近阅读</a></li>
        </ul>
    </div>
    <div class="shelf-div" id="shelf-div">
        <div class="shelf-mng">
					<span class="pg">
						<strong id="shelf-count">2</strong>/300					</span>
            <a href="#nogo" class="mng-btn mng-start">管理</a>
            <a href="#nogo" class="mng-btn mng-done">完成</a>
        </div>
        <section class="min-list shelf-list">
            <ul id="shelf-list">
                <li id="shelf-495299" data-bid="495299">
                    <dl>
                        <dt><a href="https://m.zhulang.com/495299/index.html"><img src="https://s.zhulang.com/images/s.gif" data-src="https://i.zhulang.com/book_cover/image/49/52/495299_x160.jpg"></a></dt>
                        <dd>
                            <a href="https://m.zhulang.com/495299/index.html"><h3>万古神龙变</h3></a>
                            <a href="https://m.zhulang.com/495299/418091.html"><p>更新：第一千八十二章 怕丢人</p></a>

                        </dd>
                    </dl>
                    <div class="opt">


                    </div>
                </li>
                <li id="shelf-513820" data-bid="513820">
                    <dl>
                        <dt><a href="https://m.zhulang.com/513820/index.html"><img src="https://s.zhulang.com/images/s.gif" data-src="https://i.zhulang.com/book_cover/image/51/38/513820_x160.jpg"></a></dt>
                        <dd>
                            <a href="https://m.zhulang.com/513820/index.html"><h3>仙魔实录</h3></a>
                            <a href="https://m.zhulang.com/513820/316906.html"><p>更新：第四百二十七章 能活多久是多久</p></a>

                        </dd>
                    </dl>
                    <div class="opt">


                    </div>
                </li>
            </ul>
        </section>
    </div>
    <div class="usr-btm">
        <a href="https://m.zhulang.com/login/logout.html" class="blue fl">退出登录</a>
        <a href="#top" class="blue fr"><i class="icon iconfont">&#xe609;</i> 返回顶部</a>
    </div>
    <div class="btm-nav">
        <div class="btm-sch">
            <form action="https://m.zhulang.com/search/index.html" method="">
                <input type="text" name="k" value="" maxlength="50" placeholder="搜索书名、作者、分类等">
                <button></button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://s.zhulang.com/wap/v2/js/lib/mlib.js"></script>
<script type="text/javascript" src="https://s.zhulang.com/wap/v2/js/plugins/swiper.min.js"></script>
<script type="text/javascript" src="https://s.zhulang.com/wap/pub/v2/js/bundle/shelf-2c37374aaf.js"></script>
<script type="text/javascript">
    $(function(){$("#shelf-div .mng-btn").on("click",function(){var a=$(this),t=$("#shelf-list"),d=t.find("li");d.length&&(a.hasClass("mng-start")&&d.each(function(){var a=$(this),t=a.data("bid"),d=a.find("div.opt"),s='<a class="del" data-act="del-from-shelf" data-api="/user/shelf/delete/book_id/'+t+'/shelf_id/0.html" data-post="bookid='+t+'">删除</a>';d.append(s)}),a.hasClass("mng-done")&&t.find(".opt .del").remove())})});
</script>
</body>
</html>