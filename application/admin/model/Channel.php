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

class Channel extends Model
{

    public $pk = 'id';

    /**
     * @param $off 1:分页；0：不分页
     * @param array $params
     * @return null|\think\Paginator|\think\paginator\Collection
     */
    public function getChannelLists($off = 0, $params = [])
    {
        $query = Db::table('ien_channel')->field('id,channel,channel_name,channel_address')->where('is_delete', 0)->order('id ASC');
        if ($off) {
            $list = $query->where($params)->paginate(15, false, [
                'query' => request()->param(),
            ]);
        } else {
            $list = $query->select();
        }
        return $list;
    }


    /**
     * 渠道详情
     * @param $id
     */
    public function channelDetail($id)
    {
        $info = Db::table('ien_channel')->where('id', $id)->field('id,channel,channel_name,channel_address')->find();
        return $info ? $info : null;
    }
    //获取指定渠道文章

    /**
     * @param $id
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getChannelArticle($id)
    {
        $info = Db::table('ien_channel')->alias('c')->join('ien_book as b', 'c.bid = b.id', 'LEFT')
            ->join('ien_chapter as ch', 'c.zid = ch.id', 'LEFT')
            ->field('c.id,c.channel,b.title as btitle,ch.title as chtitle')
            ->where('c.id', $id)
            ->where('c.is_delete', 0)
            ->find();
        return $info ? $info : null;
    }

    public function channelArticleList()
    {
        $list = Db::table('ien_channel')->alias('c')
            ->join('ien_book b', 'c.bid = b.id', 'LEFT')
            ->join('ien_chapter ch', 'c.zid = ch.id', 'LEFT')
            ->field('c.id,c.channel,b.title as b_title,ch.title as ch_title')
            ->where('c.is_delete', 0)
            ->paginate(15);
        return $list ? $list : null;
    }

    /**
     * 添加渠道
     * @param array $params
     * @return bool|int|string
     */
    public function addChannelInfo($params = [])
    {

        $addId = Db::table('ien_channel')->insert($params);
        return $addId ? $addId : false;
    }

    /**
     * 修改渠道
     * @param $id
     * @param array $params
     * @return bool|int|string
     */
    public function editChannelInfo($id, $params = [])
    {
        $res = Db::table('ien_channel')->where('id', $id)->update($params);
        return $res ? $res : false;
    }

    /**
     * 移除渠道
     * @param $id
     * @param array $params
     */
    public function delChannelStatus($id, $params = [])
    {
        $res = Db::table('ien_channel')->where('id', $id)->update($params);
        return $res ? $res : false;
    }

    /**
     * 获取渠道书籍章节id
     * @param $id
     */
    public function getChannelDetail($id)
    {
        $info = Db::table('ien_channel')->where('id', $id)->field('bid,zid')->find();
        return $info ? $info : null;
    }


}