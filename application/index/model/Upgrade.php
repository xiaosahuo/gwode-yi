<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/11
 * Time: 14:56
 */

namespace app\index\model;

use think\Model;
use think\Db;

class Upgrade extends Model
{
    public $pk = 'id';

    /**
     * 获取当前升级包范围内数据
     * @param $channel_id
     * @param $versionCode
     * @return array|false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getUpgradeRenew($channel_id,$versionCode)
    {
        $res = Db::table('ien_upgrade')
            ->where('versionCode', '>', $versionCode)
            //添加
            ->where('channel_id', $channel_id)
            //结束
            ->field(['renew'])
            ->select();
        return $res;
    }

    public function getUpgradeInfo($channel_id)
    {
        $info = Db::table('ien_upgrade')
            ->field(['address' => 'versionUrl','version' => 'versionName','content' => 'versionTip','versionCode'])
            ->where('channel_id', $channel_id)
            ->where('status', 1)
            ->find();
        return $info;
    }
}