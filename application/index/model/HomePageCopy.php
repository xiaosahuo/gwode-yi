<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/11
 * Time: 14:56
 */

namespace app\index\model;

use think\Model;
use think\Db;

class HomePageCopy extends Model
{
    public $pk = 'id';

    /**
     * 获取符合条件的首页书籍ID列表
     * @param $type
     * @param $sex
     * @return array|false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getTypeList($type,$sex = '')
    {
        $query = Db::table('ien_home_page_copy')
            ->field('bookid')
            ->where('type',$type);//5表示男女系列
        if(!$sex){
            $list = $query->select();
        } else {
            $list =$query->where('sex',$sex)->select();
        }
        return $list;
    }
}