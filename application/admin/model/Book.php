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

class Book extends Model
{
    public $pk = 'id';

    /**
     * 获取所有数据
     * @return false|mixed|null|\PDOStatement|string|\think\Collection
     */
    public function booklists($off = 0)
    {
        $query = DB::table('ien_book')->field('id,title,sex,status')->where('status', 1);
        if ($off) {
            $list = $query->order('sort asc,id desc')->paginate(16);
        } else {
            $list = $query->order('id asc')->select();
        }
        return $list;
    }

    /**
     * 获取所有小说
     * @return null|\think\Paginator|\think\paginator\Collection
     */
    public function getBookAlllists()
    {
        $list = Db::table('ien_book')->field('id,title,sex,status')->order('id desc,status desc')->paginate(16);
        return $list;
    }

    /**
     * 修改书籍上下架状态
     * @param $id
     * @param array $params
     * @return bool|int|string
     */
    public function bookStatus($id, $params = [])
    {
        $res = Db::table('ien_book')->where('id', $id)->update($params);
        return $res;
    }
}