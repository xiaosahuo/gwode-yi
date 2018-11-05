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

class Admin extends Model
{
    public $pk = 'id';

    /**
     * 获取管理员列表
     * @return false|mixed|null|\PDOStatement|string|\think\Collection
     */
    public function lists()
    {
        $list = Db::table('ien_app_admin')->field('id,username,status')->where('role_id', '>', 1)->paginate(15, false, [
            'query' => request()->param(),
        ]);
        return $list;
    }

    /**
     * 调整管理员状态
     * @param $id
     * @param array $params
     * @return bool|int|string
     */
    public function adminStatus($id, $params = [])
    {
        $res = Db::table('ien_app_admin')->where('id', $id)->update($params);
        return $res ? $res : false;
    }

    /**
     * 添加管理员
     * @param array $params
     * @return int|string
     */
    public function addAdmin($params = [])
    {
        $res = Db::table('ien_app_admin')->insertGetId($params);
        return $res;
    }


    /**
     * 删除对应管理员
     * @param $id
     * @return bool|int
     */
    public function delAdmin($id)
    {
        $res = Db::table('ien_app_admin')->delete($id);
        return $res ? $res : false;
    }

    /**
     * 获取对应管理员的详情信息
     * @return false|mixed|null|\PDOStatement|string|\think\Collection
     */
    public function getAdminInfo($username)
    {
        $info = Db::table('ien_app_admin')->where('username', $username)->field('id')->find();
        return $info;
    }

    /**
     * 获取登录管理员的信息
     * @param $username
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getAdminLoginInfo($username)
    {
        $info = Db::table('ien_app_admin')->field('id,username,password')->where('username', $username)->where('status', 1)->find();
        return $info;
    }

    /**
     * 管理员登录后信息更新
     * @param $id
     * @param array $params
     */
    public function adminLoginInfoUpdate($id, $params = [])
    {
        Db::table('ien_app_admin')->where('id', $id)->update($params);
    }


}