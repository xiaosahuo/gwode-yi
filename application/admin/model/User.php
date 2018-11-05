<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/11
 * Time: 14:41
 */

namespace app\admin\model;

use think\Model;
use think\Db;

class User extends Model
{

    public $pk = 'id';


    /**
     * 获取用户列表
     * @param array $params
     * @return null|\think\Paginator|\think\paginator\Collection
     */
    public function getUserLists($params = [])
    {
        $list = Db::table('ien_app_user')->alias('u')->join('ien_channel as c', 'u.channel_id = c.id', 'LEFT')
            ->field('u.id,u.username,u.img,u.nickname,u.phone,u.create_time,u.open_id,u.logintype,c.channel')
            ->where($params)
            ->order('u.id DESC')
            ->paginate(15, false, [
                'query' => request()->param(),
            ]);
        return $list ? $list : null;
    }


}