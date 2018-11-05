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

class Chapter extends Model
{
    public $pk = 'id';


    /**
     * 获取渠道书籍的所有章节信息
     * @param $id
     */
    public function getChapterList($bid, $off = 0)
    {
        $query = Db::table('ien_chapter')->where('bid', $bid)->field('id,title,isvip,idx')->order('idx ASC');
        if ($off) {
            $list = $query->paginate(15);
        } else {
            $list = $query->select();
        }
        return $list;
    }

    public function setChapterVipStatus($id, $params = [])
    {
        $res = Db::table('ien_chapter')->where('id', $id)->update($params);
        return $res;
    }

}