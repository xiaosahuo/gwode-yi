<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/11
 * Time: 14:56
 */

namespace app\admin\model;

use think\Model;
use think\Db;

class Upgrade extends Model
{
    public $pk = 'id';

    /**
     * 获取所有数据 不分页
     * @return false|mixed|null|\PDOStatement|string|\think\Collection
     */
    public function lists()
    {
        $list = Db::table('ien_upgrade')->alias('u')->join('ien_channel as c', 'u.channel_id = c.id', 'LEFT')
            ->field('u.*,c.channel')->order('u.version DESC,u.id DESC')->paginate(10);
        return $list;
    }

    /**
     * 查看详情
     * @param $id
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function details($id)
    {
        $info = Db::table('ien_upgrade')->where('id', $id)->find();
        return $info;
    }

    public function createData($params = [])
    {
        $res = Db::table('ien_upgrade')->insert($params);
        return $res;
    }

    /**
     * 修改升级信息
     * @param $id
     * @param array $params
     * @return bool|int|string
     */
    public function modifyData($id, $params = [])
    {
        $res = Db::table('ien_upgrade')->where('id', $id)->update($params);
        return $res;
    }

    /**
     * 删除信息
     * @param $id
     */
    public function del($id)
    {
        $res = Db::table('ien_upgrade')->delete($id);
        return $res;
    }

    /**
     * 获取最新版本号
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getLastVersionCode()
    {
        $info = Db::table('ien_upgrade')->field(['versionCode'])->order('versionCode desc')->limit(1)->find();
        return $info;
    }

    /**
     * 修改升级包状态
     * @param $id
     * @param array $params
     * @return bool|int|string
     */
    public function upgradeStatus($id, $params = [])
    {
        if ($params['status'] == 1) {
            Db::table('ien_upgrade')->where('id', $id)->update($params);
            $upgradeInfo = Db::table('ien_upgrade')->where('id', $id)->field('channel_id')->find();
            $res = Db::table('ien_upgrade')->where('id', '<>', $id)->where('channel_id', $upgradeInfo['channel_id'])->setField('status', 0);
        } else {
            $res = Db::table('ien_upgrade')->where('id', $id)->update($params);
        }

        return $res;
    }

    /**
     * 获取对应渠道的升级信息
     * @param $channel
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getUpgradeInfo($channel)
    {
        $channelInfo = Db::table('ien_channel')->where('channel', $channel)->field('id')->find();
        $upgradeInfo = Db::table('ien_upgrade')->where('channel_id', $channelInfo['id'])->where('status', 1)->limit(1)->find();
        return $upgradeInfo;
    }
}