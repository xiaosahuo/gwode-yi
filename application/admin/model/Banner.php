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

class Banner extends Model
{
    public $pk = 'id';

    /**
     * 获取banner对应书籍信息列表
     * @return false|mixed|null|\PDOStatement|string|\think\Collection
     */
    public function bannerLists()
    {
        $list = Db::table('ien_banner')->alias('ba')->join('ien_book b', 'ba.bookid = b.id', 'LEFT')
            ->field('ba.id,ba.public,ba.bookid,ba.sex,b.title')
            ->select();
        return $list ? $list : null;
    }

    /**
     * 获取对应banner的详情信息
     * @return false|mixed|null|\PDOStatement|string|\think\Collection
     */
    public function getBannerInfo($id)
    {
        $info = Db::table('ien_banner')->where('id', $id)->field('id,bookid,sex')->find();
        return $info ? $info : null;
    }

    /**
     * 添加banner数据
     * @param array $params
     * @return bool|int|string
     */
    public function addBannerInfo($params = [])
    {
        $res = Db::table('ien_banner')->insert($params);
        return $res ? $res : false;
    }

    /**
     * 编辑对应banner数据
     * @param $id
     * @param array $params
     * @return bool|int|string
     */
    public function editBannerInfo($id, $params = [])
    {
        $addId = Db::table('ien_banner')->where('id', $id)->update($params);
        return $addId ? $addId : false;
    }

    /**
     * 删除对应banner
     * @param $id
     * @return bool|int
     */
    public function delBanner($id)
    {
        $res = Db::table('ien_banner')->delete($id);
        return $res ? $res : false;
    }
}