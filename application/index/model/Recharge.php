<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/11
 * Time: 14:42
 */

namespace app\index\model;

use think\Model;
use think\Db;

class Recharge extends Model
{

    public function saveData($params = [])
    {
        $res = Db::table('ien_app_recharge')->insert($params);
        return $res;
    }

    public function getOrderStatus($order_sn)
    {
        $info = Db::table('ien_app_recharge')->where(['payid' => $order_sn])->field('status')->find();
        return $info;
    }

    public function getOrderInfo($order_sn)
    {
        $info = Db::table('ien_app_recharge')->where(['payid' => $order_sn])->find();
        return $info;
    }

    public function updateOrderInfo($order_sn,$params = [])
    {
        $res = Db::table('ien_app_recharge')->where('status',0)->where(['payid' => $order_sn])->update($params);
        return $res;
    }

    public function getRecodeLog($uid)
    {
        $list = Db::table('ien_app_recharge')->field(['money','paytime','type'])->where('uid',$uid)->where('status',1)->order('paytime DESC')->select();
        return $list;
    }
}