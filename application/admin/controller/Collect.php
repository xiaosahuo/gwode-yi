<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/10/18
 * Time: 11:16
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Model;
use app\admin\model\Book as BookModel;
use app\admin\model\Channel as ChannelModel;
use app\admin\model\Chapter as ChapterModel;

class Collect extends Controller
{
    const APIURL = 'https://api.tengwen.com/gbook.php';
    const KEY = 'sosx001jk)Kjfk2%$';
    public $url;
    public $key;
    public $time;
    public $random;
    public $sign;
    public $params;


    protected function _initialize()
    {
        $this->url = self::APIURL;
        $this->key = self::KEY;
        $this->time = time();
        $this->random = randomString();
        $this->sign = getSign($this->key, $this->random, $this->time);
        $this->params = [
            'key' => $this->key,
            'time' => $this->time,
            'random' => $this->random,
            'sign' => $this->sign
        ];
    }

    /**
     * 获取书籍数据
     */
    public function getBookInfo()
    {
        $this->params['mod'] = 'book';
        $book = httpPost($this->url, $this->params);
        $bookinfo = json_decode($book, true);

        // dump($bookinfo['data']);die;
        if (isset($bookinfo['data'])) {
            $params = [];
            foreach ($bookinfo['data'] as $key => $value) {
                $params[$key]['cid'] = 3;
                $params[$key]['uid'] = 1;
                $params[$key]['model'] = 6;
                $params[$key]['title'] = $value['name'];
                $params[$key]['create_time'] = strtotime($value['time']);
                $params[$key]['update_time'] = strtotime($value['time']);
                $params[$key]['sort'] = 0;
                $params[$key]['status'] = 1;
                $params[$key]['view'] = 0;
                $params[$key]['trash'] = 1;
                $params[$key]['image'] = 0;
                $params[$key]['tstype'] = $this->getBookCate($value['catname']);
                $params[$key]['zuozhe'] = $value['writer'];
                $params[$key]['zishu'] = 207668;
                $params[$key]['zhishu'] = 90;
                $params[$key]['tj'] = 0;
                $params[$key]['desc'] = $value['descp'];
                $params[$key]['xstype'] = $this->getBookType($value['state']);
                $params[$key]['tips'] = 100;
                $params[$key]['gzzj'] = 0;
                $params[$key]['score'] = 34;
                $params[$key]['ishot'] = 0;
                $params[$key]['free_stime'] = 0;
                $params[$key]['free_etime'] = $value['id'];
                $params[$key]['isfree'] = 0;
                $params[$key]['public'] = $value['pic'];
                $params[$key]['consume'] = 0;
                $params[$key]['sex'] = $this->getBookCateSex($value['catname']);
            }
            //dump($params);die;
            $res = Db::table('ien_book_copy')->insertAll($params);
            if ($res) {
                $this->success("书籍入库成功");
            } else {
                $this->error("书籍入库失败");
            }
        } else {
            $this->error("参数错误");
        }

    }

    //
    public function getBookCate($catname)
    {
        switch ($catname) {
            case '其它':
                $tstype = 1;
                break;
            case '都市':
                $tstype = 2;
                break;
            case '灵异':
                $tstype = 3;
                break;
            case '现言':
                $tstype = 4;
                break;
            case '古言':
                $tstype = 5;
                break;
            case '玄幻':
                $tstype = 6;
                break;
            default:
                $tstype = 1;
                break;
        }
        return $tstype;
    }

    public function getBookCateSex($catname)
    {
        if ($catname == "都市" || $catname == "现言") {
            return 2;
        } else {
            return 1;
        }
    }

    public function getBookType($type)
    {
        if ($type == "完结") {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 获取章节数据
     */
    public function getChapterInfo()
    {
        $book_id = input('book_id/d', 258);
        $this->params['mod'] = 'chapter';
        $this->params['book_id'] = $book_id;
        $chapter = httpPost($this->url, $this->params);
        $chapterinfo = json_decode($chapter, true);
        if (isset($chapterinfo['data'])) {
            $newArray = $chapterinfo['data'];
            $params = [];

            foreach ($newArray as $key => $val) {
                $params[$key]['cid'] = 5;
                $params[$key]['uid'] = 1;
                $params[$key]['model'] = 7;
                $params[$key]['title'] = $val['name'];
                $params[$key]['status'] = 1;
                $params[$key]['update_time'] = $val['id'];
                $params[$key]['trash'] = 1;
                $params[$key]['isvip'] = $val['is_vip'];
                $params[$key]['bid'] = $book_id;
                $params[$key]['idx'] = $val['num'];
            }
            $res = Db::table('ien_chapter')->insertAll($params);
            if ($res) {
                $this->success("章节入库成功");
            } else {
                $this->error("章节入库失败");
            }
        } else {
            $this->error("参数错误");
        }


    }

    public function getChapterContent()
    {
        $book_id = input('book_id/d', 110);
        $start = input('start/d', 1);
        $end = input('end/d', 20);
        $chapterList = Db::table('ien_chapter')->field('id,update_time,bid')->where('trash', 1)->where('bid', $book_id)->order('id asc')->page($start, $end)->select();
        $chapterModel = new ChapterModel();
        foreach ($chapterList as $key => $val) {
            $res = $this->getContentInfo($val['update_time']);
            $chapterList[$key]['content'] = $res;
            /*$data['content'] = $res;
            Db::table('ien_chapter')->where('id',$val['id'])->update($data);*/
        }
        $result = $chapterModel->isUpdate(true)->saveAll($chapterList);
        if ($result) {
            $this->success("章节内容入库成功");
        } else {
            $this->error("章节内容入库失败");
        }
    }

    /**
     * 获取章节内容
     */
    public function getContentInfo($chapter_id)
    {
        $this->params['mod'] = 'body';
        $this->params['chapter_id'] = $chapter_id;
        $body = httpPost($this->url, $this->params);

        $bodyinfo = json_decode($body, true);

        if (isset($bodyinfo['data'])) {
            return $bodyinfo['data']['body'];
        }
    }
}