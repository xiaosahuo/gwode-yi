<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/11
 * Time: 14:41
 */

namespace app\index\model;

use think\Model;
use think\Db;

class Notice extends Model
{


    public function getBookNoticeId($bookId)
    {
        $info = Db::table('ien_app_notice')->where('book_id',$bookId)->field('id')->order('id desc')->find();
        return $info;
    }






}