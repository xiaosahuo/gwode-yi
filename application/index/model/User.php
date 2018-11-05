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

class User extends Model
{

    public $pk = 'id';


    /**
     * 根据ID获取用户信息
     * @param $id
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getUserInfoById($id)
    {
        $res = Db::table('ien_app_user')
            ->field(['money','isvip','expire_time'])
            ->where('id',$id)
            ->find();
        return $res;

    }

    /**
     * 根据手机号获取用户信息
     * @param $phone
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getUserInfoByPhone($phone)
    {
        $userinfo = Db::table('ien_app_user')->field(['id','username','img','nickname','sex','introduce','phone','isvip','money','expire_time','open_id'])->where('username',$phone)->find();
        return $userinfo;
    }

    /**
     * 根据openid获取用户信息
     * @param $openid
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getUserInfoByOpenid($openid)
    {
        $res = Db::table('ien_app_user')->field(['id','username','img','nickname','sex','introduce','phone','isvip','money','expire_time'])->where('open_id',$openid)->find();
        return $res;
    }

    /**
     * 保存用户信息
     * @param $data
     * @return int|string
     */
    public function saveInfo($data)
    {
        $res = Db::table('ien_app_user')->insert($data);
        return $res;
    }

    /**
     * 更新当前用户的信息
     * @param $uid
     * @param array $params
     * @return int|string
     */
    public function updateUserData($uid,$params = [])
    {
        $res = Db::table('ien_app_user')->where('id',$uid)->update($params);
        return $res;
    }

    public function updateUserMoney($where = [],$params = [])
    {
        $res = Db::table('ien_app_user')->where($where)->update($params);
        return $res;
    }

    /**
     * 获取当前用户的基本信息
     * @param $uid
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getUserAllInfoById($uid)
    {
        $info = Db::table('ien_app_user')
            ->field(['id','username','img','nickname','sex','introduce','phone','isvip','money','expire_time'])
            ->where('id',$uid)
            ->find();
        return $info;
    }

    public function noVipMoneyInc($uid,$money)
    {
        $res = Db::table('ien_app_user')->where(['id' => $uid])->setInc('money', $money);
        return $res;
    }





}