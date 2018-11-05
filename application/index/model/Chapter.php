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

class Chapter extends Model
{

    public $pk = 'id';

    /**
     * 获取书籍ID最新的章节
     * @param $bookid
     * @return mixed
     */
    public function getLastChapterByBookId($bookid)
    {
        $info = Db::table('ien_chapter')->where('bid', $bookid)->order('id desc')->find();
        return $info;
    }

    /**
     * 获取书籍ID的所有章节排序数据
     * @param $bookid
     * @return array|false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getChapterByBookId($bookid)
    {
        $list = Db::table('ien_chapter')->field(['isvip' => 'isVip', 'title', 'id' => '_id'])->where('bid', $bookid)->order('idx')->select();
        return $list;
    }

    /**
     * 获取章节信息
     * @param $zid
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getChapterInfo($zid)
    {
        $info = DB::table('ien_chapter')->field(['content', 'isvip'])->where('id', $zid)->find();
        return $info;
    }

    /**
     * 获取章节的排序号
     * @param $zid
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getChapterSort($zid)
    {
        $info = Db::table('ien_chapter')->field(['idx'])->where('id',$zid)->find();
        return $info;
    }




}