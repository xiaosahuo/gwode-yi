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

class HomePageCopy extends Model
{
    public $pk = 'id';

    /**
     * 获取所有数据
     * @return false|mixed|null|\PDOStatement|string|\think\Collection
     */
    public function lists($type, $off = null)
    {
        $params = [];
        if ($off) {
            switch ($off) {
                case '2':
                    $params = ['a.seat' => 1, 'a.sex' => 2];
                    break;
                case '3':
                    $params = ['a.seat' => 2, 'a.sex' => 1];
                    break;
                case '4':
                    $params = ['a.seat' => 2, 'a.sex' => 2];
                    break;
                default:
                    $params = ['a.seat' => 1, 'a.sex' => 1];
                    break;
            }
        }
        $list = Db::table('ien_home_page_copy')->alias('a')->join('ien_book b', 'a.bookid = b.id', 'LEFT')
            ->where('a.type', $type)
            ->where($params)
            ->field('a.remark,a.wz,a.id,a.bookid,b.title,b.image,b.status')
            ->order('a.id ASC')
            ->paginate(10);
        return $list ? $list : null;
    }

    /**
     * 首页wz信息修改
     * @param $id
     * @param array $params
     * @return bool|int|string
     */
    public function wzModify($wz, $params = [])
    {
        $res = Db::table('ien_home_page_copy')->where('wz', $wz)->update($params);
        return $res;
    }

    /**
     * 首页信息修改
     * @param $id
     * @param array $params
     * @return bool|int|string
     */
    public function modify($id, $params = [])
    {
        $res = Db::table('ien_home_page_copy')->where('id', $id)->update($params);
        return $res;
    }

    /**
     * 添加数据信息
     * @param array $params
     */
    public function addData($params = [])
    {
        $addId = Db::table('ien_home_page_copy')->insert($params);
        return $addId;
    }

    /**
     * 删除信息
     * @param $id
     * @return bool|int
     */
    public function deleteData($id)
    {
        $res = Db::table('ien_home_page_copy')->delete($id);
        return $res;
    }
}