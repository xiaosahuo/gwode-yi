<?php

namespace app\admin\model;

use think\Model;
use think\Db;

class Index extends Model
{

    /**
     * @param $sex
     * @return mixed
     */
    public function bannerList($sex)
    {
        $sql = 'SELECT * FROM ien_banner where sex=?';

        $res = Db::query($sql, [$sex]);
        return $res;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getList($type)
    {
        $sql = 'SELECT a.remark as remark,a.wz,a.id,a.bookid,b.title,b.image FROM ien_home_page_copy as a LEFT JOIN ien_book AS b ON a.bookid=b.id where a.type = ? and b.status = 1 ORDER BY a.id ASC';

        $res = Db::query($sql, [$type]);
        return $res;
    }

    /**
     * @return mixed
     */
    public function bookList()
    {
        $sql = 'SELECT id,title,image FROM ien_book where status = 1';

        $res = Db::query($sql);
        return $res;
    }

    /**
     * @param $type
     * @param $sex
     * @return mixed
     */
    public function nvList($type, $sex)
    {
        $sql = 'SELECT a.wz,a.id,a.bookid,b.title,b.image FROM ien_home_page_copy as a LEFT JOIN ien_book AS b ON a.bookid=b.id where a.type = ? and a.sex = ? and b.status = 1 ORDER BY a.id ASC';

        $res = Db::query($sql, [$type, $sex]);
        return $res;
    }

    /**
     * 调整菜单状态
     * @param $id
     * @param $status
     * @return int
     */
    public function modifyMenuStatus($id, $status)
    {
        $res = Db::table('ien_app_menu')->where('id', $id)->setField('status', $status);
        return $res;
    }
}
