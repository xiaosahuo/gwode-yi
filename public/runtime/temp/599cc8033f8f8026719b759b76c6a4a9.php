<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"D:\PhpStudy\PHPTutorial\WWW\appdata\public/../application/index\view\index\login.html";i:1541222688;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta content="telephone=no,email=no" name="format-detection">
    <script src="/static/js/flexible.0.3.4.js"></script>
    <link href="https://s.zhulang.com/images/zl-logo114.png" rel="apple-touch-icon-precomposed">
    <title>登录逐浪网</title>
    <meta name="keywords" content="逐浪">
    <meta name="description" content="登录-逐浪手机小说网">
    <link rel="stylesheet" href="/static/css/zlm-b4a23cdd8b.css" type="text/css">
</head>
<body ontouchstart >
<div class="wrap">
    <header class="pg-hd">
        <a href="javascript:void(0)" class="icon fl backicon"><i class="iconfont">&#xe606;</i></a>
        <h2>登录逐浪网</h2>
        <a href="/" class="icon fr"><i class="iconfont">&#xe600;</i></a>
    </header>
    <div class="app-bar">
        <h1><p>
            <em>逐浪小说App</em>
            <span>免费看小说，天天领金币</span>
        </p>
        </h1>
        <a class="down-btn app-link" href="https://m.zhulang.com/index/app/section/login.html">点击安装</a>
    </div>

    <div class="log-fm">
        <form action="https://m.zhulang.com/login/doLogin.html" id="log-fm" method="post">
            <ul class="fm-ipt ipt-grp">
                <li>
                    <i class="icon iconfont iconuser">&#xe601;</i><input type="text" name="username" maxlength="30" placeholder="用户名/手机号" class="txt">
                </li>
                <li>
                    <i class="icon iconfont iconlock">&#xe602;</i><input type="password" name="pwd"  maxlength="30" placeholder="请输入密码" class="txt">
                </li>
            </ul>

            <input type="hidden" name="dest" value="">
            <button class="sub-btn" id="log-btn" type="button">
                <em>登录</em>
                <span><i class="icon iconfont">&#xe60e;</i> 登录中...</span>
            </button>
        </form>
        <iframe src="about:blank;" name="login-proxy" id="login-proxy" width="0" height="0" style="display:none;"></iframe>
        <div class="log-link">
            <a href="https://m.zhulang.com/register/index.html" class="reg-link fl">立即注册</a>
            <a href="javascript:" id="forget-pass" class="fr">忘记密码</a>
        </div>
    </div>
    <div class="auth-log">
        <h2><span>合作网站账号登录</span></h2>
        <div class="auth-icon">
            <a href="http://if.zhulang.com/oauth/go/wapsina" class="icon-wb"><i></i>微博</a>
            <a href="http://if.zhulang.com/oauth/go/wapwx" class="icon-wx"><i></i>微信</a>
            <a href="http://if.zhulang.com/oauth/go/wapqq" class="icon-qq"><i></i>QQ</a>
        </div>
        <div class="auth-btn">
            <a href="http://if.zhulang.com/oauth/go/wapqq" class="fl sns-qq"></a>
            <a href="http://if.zhulang.com/oauth/go/wapsina" class="fr sns-wb"></a>
        </div>
    </div>

    <style>
        .clog{ margin: 0 10px;
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 80%;
            line-height: 1.5;
            background-color: #f5f5f5;
            color: #666; }
    </style>
    <div class="clog" style="display: none;" id="clog"></div>

</div>

<script>
    var ua = navigator.userAgent;
    var zlDomains = ['zhulang.com','xxs8.com'];
    var channelID = '';
    var isInWifi = /wkbrowser|WiFikey/i.test(ua);
    var isAndroid = ua.match(/Android/i);
    var isIos = ua.match(/iPhone|iPad|iPod/i);

</script>
<script type="text/javascript" src="/static/js/lib.js"></script>
<script type="text/javascript" src="/static/js/reglog-566aff4557.js"></script>
<script type="text/javascript">

    function nmChnLogCtl(){
        var wxChn = /^31\d+$/.test(window.channelID);
        if(!wxChn) return;



        if(ua.match(/MicroMessenger/i)){
            window.location = 'http://if.zhulang.com/oauth/go/wapwx';
        }

        var isQQ = ua.match(/\s+QQ/i);

        if(isQQ && (isAndroid || isIos)){
            window.location = 	'http://if.zhulang.com/oauth/go/wapqq';
        };


    };

    $(function(){

        nmChnLogCtl();

        $("#forget-pass").on('click',function () {
            if(window.confirm('密码找回操作在手机上不便，将引导您至逐浪主站找回密码。')){
                window.location = "http://www.zhulang.com/forget/index.html"
            }
        });
    });



</script>






</body>
</html>