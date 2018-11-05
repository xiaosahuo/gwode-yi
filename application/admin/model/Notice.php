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

class Notice extends Model
{
    public $pk = 'id';

    /**
     * 保存数据
     * @param array $params
     * @return bool|int|string
     */
    public function saveData($params = [])
    {
        $res = Db::table('ien_app_notice')->insert($params);
        return $res;
    }

    /**
     * 获取推送信息列表数据
     * @param int $off
     * @return array|false|mixed|null|\PDOStatement|string|\think\Collection|\think\Paginator|\think\paginator\Collection
     */
    public function getNoticeLists($params = [])
    {
        $query = Db::table('ien_app_notice')->alias('n')
            ->join('ien_app_recharge as re', 'n.id = re.notice_id', 'LEFT')
            ->join('ien_book as b', 'n.book_id = b.id', 'LEFT')
            ->field('n.id,n.title as n_title,n.url,n.book_id,n.create_time,n.trace_id,count(re.id) as count_num,sum(re.money) as sum_money,b.title as b_title')
            ->where($params)
            ->order('n.id DESC')
            ->group('n.id');
        $list = $query->paginate(15, false, [
            'query' => request()->param()
        ]);
        return $list;

    }

    public function getNoticePage($params = [])
    {
        $query = Db::table('ien_app_notice')->alias('n')
            ->field('n.id,n.title as n_title')
            ->where($params)
            ->order('n.id DESC');
        $list = $query->paginate(15, false, [
            'query' => request()->param()
        ]);
        return $list;
    }


}