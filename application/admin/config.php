<?php


return [
    
    // 默认控制器名
    'default_controller'    => 'Index',
    // 默认操作名
    'default_action'        => 'index',

    'url_route_on' => true,

    // 'app_trace'  => true,

    'view_replace_str'=>[
        '__STATIC__' => '/static',
        '__CSS__' => '/admin/css',
        '__JS__' => '/admin/js',
    ],

    'DOMAIN'    => 'http://'.$_SERVER['SERVER_NAME'],

    //redis
    'redis_flag' => false,

    'file_upload' => './uploads/',

    'excel_url'   =>'../extend/org/vendor/',

    'sftp' => [
        'host' => '101.201.29.182', //测试 101.201.107.95  //正式 101.201.29.182
        'port' => '22', //测试 9022  //正式 22
        'user' => 'dyc_apk',
        'passwd' => 'yfb918',
    ],
    'remote_folder' => '/home/wwwroot/default/dyc/',  //测试 /home/www/dyc-apk-download   //正式 /home/wwwroot/default/dyc/
    'apk_download_url' => 'http://www.wxbgf.top/dyc',  //测试 http://download.dtbooking.com  //正式 http://www.wxbgf.top/dyc
    
];