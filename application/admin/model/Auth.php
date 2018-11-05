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

class Auth extends Model
{
    public $pk = 'id';

    /**
     * 获取权限列表
     * @return false|mixed|null|\PDOStatement|string|\think\Collection
     */
    public function lists()
    {
        $list = Db::table('ien_app_admin_auth')->field('id,auth_controller,auth_action,auth_controller_name,auth_action_name')->select();
        return $list;
    }

    /**
     * 获取对应用户的去权限规则
     * @param $userId
     */
    public function getUserAuth($userId)
    {
        $res = Db::table('ien_app_admin_rule')->where('user_id', $userId)->field('auth_rule')->find();
        return $res;
    }

    /**
     * 获取对应用户的权限ID
     * @param $userId
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getUserAuthId($userId)
    {
        $res = Db::table('ien_app_admin_rule')->field('id')->where('user_id', $userId)->find();
        return $res;
    }

    /**
     * 保存信息
     * @param $id
     * @param array $params
     */
    public function saveAuth($params = [])
    {
        $res = Db::table('ien_app_admin_rule')->insert($params);
        return $res;
    }

    /**
     * 修改信息
     * @param $id
     * @param array $params
     * @return int|string
     */
    public function modifyAuth($id, $params = [])
    {
        $res = Db::table('ien_app_admin_rule')->where('id', $id)->update($params);
        return $res;
    }

    /**
     * 获取对应的权限节点
     * @param $id
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getAdminAuth($id)
    {
        $info = Db::table('ien_app_admin_auth')->where('id', $id)->field('auth_module,auth_controller,auth_action')->find();
        return $info;
    }

    /**
     * 获取后台菜单
     * @return array|false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getMenu()
    {
        $menu = Db::table('ien_app_menu')->where('status', 1)->order('sort asc')->select();
        return $menu;
    }

}