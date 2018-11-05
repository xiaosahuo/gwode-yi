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

class Channel extends Model
{

    public $pk = 'id';

    public function getChannelInfoByChannel($channel)
    {
        $info = Db::table('ien_channel')->field(['zid','bid'])->where('channel',$channel)->where('is_delete',0)->find();
        return $info;
    }

    public function getChannelIdByChannel($channel)
    {
        $info = Db::table('ien_channel')->where('channel',$channel)->field('id')->find();
        return $info;
    }





}