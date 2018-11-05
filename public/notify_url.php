<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/15
 * Time: 14:56
 */
echo 123;die;
$request = Request::instance()->post();

if($request){
    file_put_contents('./url.log', '----'.$request,FILE_APPEND);

}else{
    $request = Request::instance()->get();
    file_put_contents('./url.log', '===='.$request,FILE_APPEND);

}
