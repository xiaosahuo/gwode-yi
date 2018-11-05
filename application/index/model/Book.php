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

class Book extends Model
{

    public $pk = 'id';


    /**
     * 根据ID获取书籍信息
     * @param $id
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getBookById($id)
    {
        $info = DB::table('ien_book')
            ->field(['desc'=>'des','public'=>'icon','id','title' => 'name','xstype'=>'status','tstype'=>'type','image','status'])
            ->where('id',$id)
            ->where('status',1)
            ->find();
        return $info;

    }

    /**
     * 获取ID不等于并且满足条件的五条数据
     * @param $id
     * @param array $data
     * @return array|false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getOtherBookById($id,$data=[])
    {
        $list =DB::table('ien_book')
            ->field(['zuozhe'=>'bookAuthor','desc'=>'bookContenUrl','public'=>'bookIconUrl','id'=>'bookId','title' => 'bookName','view'=>'bookNum','tstype'=>'bookType','image','status'])
            ->where('id','<>',$id)
            ->where('status',1)
            ->where($data)
            ->limit(5)
            ->select();
        return $list;

    }

    /**
     * 获取符合条件的总数
     * @param array $params
     * @return float|int|string
     */
    public function getBookCount($params = [])
    {
        $total = Db::table('ien_book')->where($params)->where('status',1)->count();
        return $total;
    }

    /**
     * 根据书籍ID获取书籍信息
     * @param $bid
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getBookInfoById($bid)
    {
        $info = Db::table('ien_book')
            ->field(['zuozhe'=>'bookAuthor','desc'=>'bookContenUrl','public'=>'bookIconUrl','id'=>'bookId','title' => 'bookName','view'=>'bookNum','tstype'=>'bookType','image','status'])
            ->where('id',$bid)
            ->where('status',1)
            ->find();
        return $info;
    }





}