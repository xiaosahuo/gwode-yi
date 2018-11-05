<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/11
 * Time: 14:42
 */

namespace app\admin\model;

use think\Model;
use think\Db;

class Recharge extends Model
{
    /**
     * 获取充值记录数据
     * @param $channelId
     * @return array|false|mixed|\PDOStatement|string|\think\Collection
     */
    public function lists($channelId)
    {
        $list = Db::table('ien_app_recharge')->field('type,status,money,uid,paytype,channel_id')->where('channel_id', $channelId)->select();
        return $list;
    }

    /**
     * 根据时间条件查询数据
     * @param array $params
     * @return array|false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getLists($params = [])
    {
        $list = Db::table('ien_app_recharge')->where($params)->field('paytype,status,money')->select();
        return $list;
    }

    /**
     * 详情页数据列表
     * @param array $params
     * @return array|\think\Paginator|\think\paginator\Collection
     */
    public function detailLists($params = [], $where = [], $off = 0)
    {
        $query = Db::table('ien_app_recharge')->alias('re')->join('ien_app_user u', 're.uid = u.id', 'LEFT')->field('re.type,re.status,re.money,re.uid,re.paytype,re.channel_id,re.addtime,re.payid,re.paytime,u.username')->where($params)->where($where)->order('re.addtime DESC');
        if ($off) {
            $list = $query->select();
        } else {
            $list = $query->paginate(15, false, [
                'query' => request()->param(),
            ]);
        }
        return $list;
    }

    /**
     * 推送消息详情数据列表
     * @param $start
     * @param $end
     * @param $bookId
     * @param int $off
     * @return array|false|\PDOStatement|string|\think\Collection|\think\Paginator|\think\paginator\Collection
     */
    public function getNoticeDetail($params = [], $off = 0)
    {
        $query = Db::table('ien_app_recharge')->alias('re')->join('ien_app_user u', 're.uid = u.id', 'LEFT')->field('re.type,re.status,re.money,re.uid,re.paytype,re.channel_id,re.addtime,re.payid,re.paytime,u.username')->where($params)->order('re.addtime DESC');
        if ($off) {
            $list = $query->select();
        } else {
            $list = $query->paginate(15, false, [
                'query' => request()->param(),
            ]);
        }
        return $list ? $list : [];
    }

}