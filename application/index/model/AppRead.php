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

class AppRead extends Model
{

    public $pk = 'id';

    /**
     * 根据条件获取阅读记录信息
     * @param $zid
     * @param $uid
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getChapterInfo($zid,$uid)
    {
        $info = Db::table('ien_app_read')->where(['zid'=>$zid,'uid'=>$uid])->find();
        return $info;
    }

    /**
     * 获取符合条件的数据
     * @param array $params
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getAppReadInfo($params = [])
    {
        $info = Db::table('ien_app_read')->where($params)->find();
        return $info;
    }

    /**
     * 根据条件修改数据
     * @param array $where
     * @param array $params
     * @return int|string
     */
    public function updateAppReadInfo($where = [])
    {
        $res = Db::table('ien_app_read')->where($where)->setField('update_time',time());
        return $res;
    }

    /**
     * 新增阅读记录
     * @param $data
     * @return int|string
     */
    public function saveData($data)
    {
        $res = Db::table('ien_app_read')->insert($data);
        return $res;
    }





}