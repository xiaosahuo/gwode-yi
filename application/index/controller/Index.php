<?php

namespace app\index\controller;

use alipay\aop\AopClient as AopClient;
use alipay\aop\request\AlipayTradeAppPayRequest as AlipayTradeAppPayRequest;
use sms\sendSms;
use think\Db;
use think\Loader;
use think\Request;
use think\Config;
use think\Controller;
use think\Cache;
use app\index\model\Index as IndexModel;
use app\index\model\Upgrade as UpgradeModel;
use app\index\model\User as UserModel;
use app\index\model\Chapter as ChapterModel;
use app\index\model\Book as BookModel;
use app\index\model\HomePageCopy as HPCModel;
use app\index\model\AppRead as AppReadModel;
use app\index\model\Channel as ChannelModel;
use app\index\model\Recharge as RechargeModel;
use app\index\model\Notice as NoticeModel;

class Index extends Controller
{

    const ALI_PAY_URL = 'https://openapi.alipay.com/gateway.do';
    const WX_PAY_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    /*
     *登陆注册
     */
    public function login()
    {
        return $this->fetch();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/index",
     *   tags={"书城"},
     *   summary="首页数据",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *首页数据
     */
    public function index()
    {
        header("content-type:text/html;charset=utf-8");
        $data = [
            'seat' => 3
        ];
         $groom = Db::table('ien_home_page_copy')->field('bookid')->where($data)->limit(3)->select();
         foreach ($groom as $k => $v){
             $books[$k] = Db::table('ien_book')->field(['public','title'])->where('id',$v['bookid'])->find();
         }
         $this->assign("books",$books);
        return $this->fetch();
    }


    /*
     * 获取书籍图片
     */
    public function get_file_path($_var_5)
    {
        if ($_var_5 == "") {
            $domain = Config::get('APP_DOMAIN');
            return $domain . '/index/img/no_cover.jpg';
        }
        if (isHttp($_var_5)) {
            return $_var_5;
        } else {
            return 'http:' . $_var_5;
        }
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/change",
     *   tags={"书城"},
     *   summary="首页换一换",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     * 首页换一换
     */
    public function change()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        $date = array();
        if (isset($data_json->id) && isset($data_json->type) && isset($data_json->page)) {
            if ($data_json->id == '-1' || $data_json->id) {
                switch ($data_json->type) {
                    case 1 :    //type1表示是男精选
                        $date = ['b.seat' => 1, 'b.sex' => 1];
                        break;
                    case 2 :    //type1表示是女精选
                        $date = ['b.seat' => 1, 'b.sex' => 2];
                        break;
                    case 3 :    //type3表示是本周男性精选
                        $date = ['b.seat' => 2, 'b.sex' => 1];
                        break;
                    case 4 :    //type4表示是本周女性精选
                        $date = ['b.seat' => 2, 'b.sex' => 2];
                        break;
                    default:
                        break;
                }
                $page = $data_json->page;
                $res = Loader::model('Index')->changeModel($date, $page);
                foreach ($res as $k => $v) {
                    $v['bookIconUrl'] = $this->get_file_path($v['bookIconUrl']);
                    $res[$k] = $v;
                }
                return successEncrypt($res);
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/bookDetails",
     *   tags={"书城"},
     *   summary="书籍详情页",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *书籍详情页
     */
    public function bookDetails()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();

        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->bookid) && isset($data_json->sex)) {
            $bookModel = new BookModel();
            $chapterModel = new ChapterModel();
            $indexModel = new IndexModel();
            if (isset($data_json->id)) {
                if ($data_json->id == '-1' || $data_json->id) {//$data_json->id表示用户id -1为默认id
                    $bookid = intval($data_json->bookid);
                    $sex = intval($data_json->sex);

                    if ($bookid) {
                        $book = $bookModel->getBookById($bookid);

                        $book['icon'] = $this->get_file_path($book['icon']);

                        $data = [
                            'tstype' => $book['type']
                        ];

                        $l = $bookModel->getOtherBookById($bookid, $data);

                        foreach ($l as $k => $v) {
                            $v['bookIconUrl'] = $this->get_file_path($v['bookIconUrl']);
                            $l[$k] = $v;
                        }
                        $chapter = $chapterModel->getChapterByBookId($bookid);
                        $total = count($chapter);
                        $news = $chapterModel->getLastChapterByBookId($bookid);
                        $tstype = $indexModel->getCmsField(49);
                        $tstype = explode("\r\n", $tstype['options']);
                        $cate = $book['type'] - 1;
                        if (!$tstype[$cate]) {
                            $book['type'] = '暂无';
                        } else {
                            $book['type'] = $tstype[$cate];
                        }
                        $book['total'] = $total;
                        $book['news'] = $news['title'];
                        $book['list'] = $l;
                        return successEncrypt($book);
                    }
                    goto ret;
                }
                goto ret;
            }
            goto ret;
        }

        ret:
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/row",
     *   tags={"书城"},
     *   summary="排行",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *排行
     */
    public function row()
    {
        $data = 'view desc';
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->id) && isset($data_json->page)) {
            if ($data_json->id == '-1' || $data_json->id) {
                $bookModel = new BookModel();
                $hpcModel = new HPCModel();
                $total = $bookModel->getBookCount();
                $num = $total / 10;
                //添加
                $arr = $hpcModel->getTypeList(4);
                $arr1 = [];
                foreach ($arr as $k => $v) {
                    $arr1[] = $v['bookid'];
                }
                //结束
                if ($data_json->page > $num) {
                    $res = array();
                    return successEncrypt($res);
                } else {
                    //添加
                    if ($data_json->page == 1) {
                        $arr = [
                            'b.type' => 4//默认排行
                        ];
                        $info = Loader::model('Index')->defaultData($arr);
                        $res = Loader::model('Index')->bookList($data, $data_json->page, $arr1);
                        $resinfo = array_merge($info, $res);
                    } else {
                        $resinfo = Loader::model('Index')->bookList($data, $data_json->page, $arr1);
                    }
                    //结束
                }
                $indexModel = new IndexModel();
                $tstype = $indexModel->getCmsField(49);

                if (!empty($tstype)) {
                    $tstype = explode("\r\n", $tstype['options']);
                    foreach ($resinfo as $k => $v) {
                        $cate = $v['bookType'] - 1;
                        $v['bookType'] = $tstype[$cate];
                        $v['bookIconUrl'] = $this->get_file_path($v['bookIconUrl']);
                        $resinfo[$k] = $v;
                    }
                    return successEncrypt($resinfo);
                }
                goto ret;
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/maleSeries",
     *   tags={"书城"},
     *   summary="男生系列",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *男生系列
     */
    public function maleSeries()
    {
        $data = ['sex' => 1];
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->id) && isset($data_json->page)) {
            if ($data_json->id == '-1' || $data_json->id) {
                $hpcModel = new HPCModel();
                $bookModel = new BookModel();
                $total = $bookModel->getBookCount($data);
                $num = $total / 6;
                //添加
                $arr = $hpcModel->getTypeList(5, 1);
                $arr1 = [];
                foreach ($arr as $k => $v) {
                    $arr1[] = $v['bookid'];
                }
                //结束
                if ($data_json->page > $num) {
                    $res = array();
                    return successEncrypt($res);
                } else {
                    //添加
                    if ($data_json->page == 1) {
                        $arr = [
                            'b.type' => 5,//男女系列
                            'b.sex' => 1//1男2女
                        ];
                        $info = Loader::model('Index')->defaultData($arr);
                        $view = 'view desc';
                        $res = Loader::model('Index')->series($view, $data_json->page, $arr1, '1');
                        $resinfo = array_merge($info, $res);

                    } else {
                        $view = 'view desc';
                        $resinfo = Loader::model('Index')->series($view, $data_json->page, $arr1, '1');
                    }
                    //结束
                }
                $indexModel = new IndexModel();
                $tstype = $indexModel->getCmsField(49);
                $tstype = explode("\r\n", $tstype['options']);
                foreach ($resinfo as $k => $v) {
                    $cate = $v['bookType'] - 1;
                    $v['bookType'] = $tstype[$cate];
                    $v['bookIconUrl'] = $this->get_file_path($v['bookIconUrl']);
                    $resinfo[$k] = $v;
                }
                //dump($resinfo);die;
                return successEncrypt($resinfo);
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/nvSeries",
     *   tags={"书城"},
     *   summary="女生系列",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *女生系列
     */
    public function nvSeries()
    {
        $data = [
            'sex' => 2
        ];
        $data_json = dataDecrypt();
        // $data_json = jsonData();

        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }

        if (isset($data_json->id) && isset($data_json->page)) {
            if ($data_json->id == '-1' || $data_json->id) {
                $hpcModel = new HPCModel();
                $bookModel = new BookModel();
                $total = $bookModel->getBookCount($data);
                $num = $total / 6;
                //添加
                $arr = $hpcModel->getTypeList(5, 2);

                $arr1 = [];
                foreach ($arr as $k => $v) {
                    $arr1[] = $v['bookid'];
                }
                //结束
                if ($data_json->page > $num) {
                    $res = array();
                    return successEncrypt($res);
                } else {
                    //添加
                    if ($data_json->page == 1) {
                        $arr = [
                            'b.type' => 5,//男女系列
                            'b.sex' => 2//1男2女
                        ];
                        $info = Loader::model('Index')->defaultData($arr);
                        $view = 'view desc';
                        $res = Loader::model('Index')->series($view, $data_json->page, $arr1, '2');
                        $resinfo = array_merge($info, $res);

                    } else {
                        $view = 'view desc';
                        $resinfo = Loader::model('Index')->series($view, $data_json->page, $arr1, '2');
                    }
                    //结束
                }
                $indexModel = new IndexModel();
                $tstype = $indexModel->getCmsField(49);
                $tstype = explode("\r\n", $tstype['options']);
                foreach ($resinfo as $k => $v) {
                    $cate = $v['bookType'] - 1;
                    $v['bookType'] = $tstype[$cate];
                    $v['bookIconUrl'] = $this->get_file_path($v['bookIconUrl']);
                    $resinfo[$k] = $v;
                }
                return successEncrypt($resinfo);
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/listBooks",
     *   tags={"书城"},
     *   summary="书库书籍数据",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     * 书库书籍数据
     */
    public function listBooks()
    {
        $data = 'create_time desc';
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }

        if (isset($data_json->id) && isset($data_json->page)) {

            if ($data_json->id == '-1' || $data_json->id) {

                $hpcModel = new HPCModel();
                $bookModel = new BookModel();
                $total = $bookModel->getBookCount();
                $num = $total / 10;
                $arr = $hpcModel->getTypeList(3);
                $arr1 = [];
                foreach ($arr as $k => $v) {
                    $arr1[] = $v['bookid'];
                }

                if ($data_json->page > $num) {
                    $res = array();
                    return successEncrypt($res);
                } else {
                    if ($data_json->page == 1) {
                        $arr = [
                            'b.type' => 3//表示书库书籍
                        ];
                        $info = Loader::model('Index')->defaultData($arr);
                        $res = Loader::model('Index')->bookList($data, $data_json->page, $arr1);
                        $resinfo = array_merge($info, $res);
                    } else {
                        $resinfo = Loader::model('Index')->bookList($data, $data_json->page, $arr1);
                    }
                }
                $indexModel = new IndexModel();
                $tstype = $indexModel->getCmsField(49);
                $tstype = explode("\r\n", $tstype['options']);

                foreach ($resinfo as $k => $v) {
                    $cate = $v['bookType'] - 1;
                    $v['bookType'] = $tstype[$cate];
                    $v['bookIconUrl'] = $this->get_file_path($v['bookIconUrl']);
                    $resinfo[$k] = $v;

                }

                return successEncrypt($resinfo);
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/bookCatalog",
     *   tags={"书架"},
     *   summary="章节目录",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *章节目录
     */
    public function bookCatalog()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->bookid)) {
            $bookid = intval($data_json->bookid);
            if ($bookid) {
                if ($bookid) {
                    $chapterModel = new ChapterModel();
                    $chapter = $chapterModel->getChapterByBookId($bookid);
                    foreach ($chapter as $k => $v) {
                        $chapter[$k]['link'] = 'http://' . $_SERVER['SERVER_NAME'] . '/index/index/chapterDetails?zid=' . $v['_id'] . '&bid=' . $bookid . '&channel=' . $data_json->channel;
                        $chapter[$k]['id'] = strval($chapter[$k]['_id']);
                    }
                    $data = [
                        'bookid' => $bookid,
                        'chapters' => $chapter,
                    ];
                    return successEncrypt($data);
                }
                goto ret;
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }

    /**
     * @SWG\Get(
     *   path="/index/Index/chapterDetails",
     *   tags={"书架"},
     *   summary="章节详情",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *章节详情
     */
    public function chapterDetails()
    {
        $param = input('param/s');
        $param = str_replace(' ', '+', $param);  //接受传参 把空格 换 +号
        $data_json = dataDecrypt($param);
        if (empty($data_json)) {
            return errorEncrypt();
        }

        if (isset($data_json->id)) {
            if ($data_json->id == '-1' || $data_json->id) {//-1表示没有用户
                $zid = input('zid/d', 80637, 'intval');
                $bid = input('bid/d', 210, 'intval');
                $channel = input('channel/s', 'cc1', 'trim');
                $chapterModel = new ChapterModel();
                $userModel = new UserModel();
                $appReadModel = new AppReadModel();
                if (!empty($zid)) {
                    $chapter = $chapterModel->getChapterInfo($zid);
                    $res['cpContent'] = $chapter['content'];
                    if ($chapter['isvip'] == '1') {
                        $id = intval($data_json->id);
                        $userMoney = $userModel->getUserInfoById($id);

                        $n = $appReadModel->getChapterInfo($zid, $id);
                        if ($userMoney['expire_time'] > time() || $n) {
                            return successEncrypt($res);
                        } else if ($userMoney['expire_time'] <= time()) {

                            if ($userMoney['money'] >= '34') {

                                $data = [
                                    'zid' => $zid,
                                    'bid' => $bid,
                                    'uid' => $id
                                ];
                                $read = $appReadModel->getAppReadInfo($data);
                                $appReadModel->updateAppReadInfo($data);
                                if ($read) {
                                    return successEncrypt($res);
                                } else {
                                    $where['id'] = $id;
                                    $update['money'] = $userMoney['money'] - 34;
                                    $userModel->updateUserMoney($where, $update);
                                    $data = [
                                        'zid' => $zid,
                                        'bid' => $bid,
                                        'uid' => $id,
                                        'create_time' => time(),
                                    ];

                                    $appReadModel->saveData($data);
                                    return successEncrypt($res);
                                }
                            }
                            $data['cpContent'] = '';
                            return successEncrypt($data);
                        }
                        $data['cpContent'] = '';
                        return successEncrypt($data);
                    }
                    return successEncrypt($res);
                }
                goto ret;
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }


    /**
     * 假设是项目中的一个API
     *
     * @SWG\Get(
     *   path="/index/Index/frameBook",
     *   tags={"书架"},
     *   summary="书架书籍",
     *   description="返回首页数据。",
     *   operationId="getMyData",
     *   produces={"application/json"},
     *  @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */


    /*
     *书架书籍
     */
    public function frameBook()
    {
        $arr = [
            'b.type' => 2//表示书架书籍
        ];
        $res = array();
        $res['list'] = Loader::model('Index')->defaultData($arr);
        $channel = input('channel/s', 'cc1', 'trim');
        $bookModel = new BookModel();
        $chapterModel = new ChapterModel();
        $channelModel = new ChannelModel();
        //如果有渠道按照相应的渠道给相应的书籍
        $ground = $channelModel->getChannelInfoByChannel($channel);
        if ($ground['zid'] != 0 && $ground['bid'] != 0) {
            $zid = $chapterModel->getChapterSort($ground['zid']);
            $res['ground'] = $bookModel->getBookInfoById($ground['bid']);
            if (empty($res['ground'])) {
                $res['ground']['bookId'] = 0;
                $res['ground']['bookAuthor'] = NULL;
                $res['ground']['bookContenUrl'] = NULL;
                $res['ground']['bookIconUrl'] = NULL;
                $res['ground']['bookName'] = NULL;
                $res['ground']['bookNum'] = 0;
                $res['ground']['bookType'] = 0;
                $res['ground']['image'] = NULL;
                $res['ground']['remark'] = NULL;
                $res['ground']['zid'] = 0;
            } else {
                $res['ground']['bookIconUrl'] = $this->get_file_path($res['ground']['bookIconUrl']);
                $res['ground']['zid'] = $zid['idx'];
            }
        } else {
            $res['ground']['bookId'] = 0;
            $res['ground']['bookAuthor'] = NULL;
            $res['ground']['bookContenUrl'] = NULL;
            $res['ground']['bookIconUrl'] = NULL;
            $res['ground']['bookName'] = NULL;
            $res['ground']['bookNum'] = 0;
            $res['ground']['bookType'] = 0;
            $res['ground']['image'] = NULL;
            $res['ground']['remark'] = NULL;
            $res['ground']['zid'] = 0;
        }


        if ($res['list']) {
            foreach ($res['list'] as $k => $v) {
                $v['bookIconUrl'] = $this->get_file_path($v['bookIconUrl']);
                $res['list'][$k] = $v;
            }
        } else {
            $res['list']['bookAuthor'] = NULL;
            $res['list']['bookContenUrl'] = NULL;
            $res['list']['bookIconUrl'] = NULL;
            $res['list']['bookId'] = 0;
            $res['list']['bookName'] = NULL;
            $res['list']['bookNum'] = 0;
            $res['list']['bookType'] = 0;
            $res['list']['image'] = NULL;
            $res['list']['remark'] = NULL;
        }
        return successEncrypt($res);
    }

    /**
     * @SWG\Get(
     *   path="/index/Index/recommend",
     *   tags={"书城"},
     *   summary="关键字推荐",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *关键字推荐
     */
    public function recommend()
    {
        $arr = [
            'b.type' => 6//推荐书籍
        ];
        $res = Loader::model('Index')->defaultData($arr);
        if ($res) {
            $data = [];
            foreach ($res as $k => $v) {
                $data[$k]['word'] = $v['remark'];
                $data[$k]['bookId'] = $v['bookId'];
            }
            return successEncrypt($data);
        } else {
            return errorEncrypt();
        }
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/search",
     *   tags={"书城"},
     *   summary="搜索接口",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *搜索接口
     */
    public function search()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->id) && isset($data_json->word)) {
            if ($data_json->id == '-1' || $data_json->id) {
                if ($data_json->word) {
                    $data = model('Index')->vague($data_json->word);
                    $indexModel = new IndexModel();
                    $tstype = $indexModel->getCmsField(49);
                    $tstype = explode("\r\n", $tstype['options']);
                    foreach ($data as $k => $v) {
                        $cate = $v['bookType'] - 1;
                        if (!$tstype[$cate]) {
                            $v['bookType'] = '暂无';
                        } else {
                            $v['bookType'] = $tstype[$cate];
                        }
                        $v['bookIconUrl'] = $this->get_file_path($v['bookIconUrl']);
                        $data[$k] = $v;
                    }
                    return successEncrypt($data);
                }
                goto ret;
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/upload",
     *   tags={"我的"},
     *   summary="我的资料",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *我的资料
     */
    public function upload()
    {
        header("content-type:text/html;charset=utf-8");
        $file = request()->file('img');
        $request = Request::instance()->post();
        $data_json = dataDecrypt($request['param']);
        // $data_json = jsonData();
        $this->checkVipOver($data_json);
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->uid) && isset($data_json->username) && isset($data_json->nickname) && isset($data_json->sex) && isset($data_json->introduce)) {
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'image');
                if ($info) {
                    $image = $info->getSaveName();
                } else {
                    $msg = '上传失败';
                    return errorEncrypt($msg);
                }
                $image = str_replace("\\", "/", $image);
                $data = [
                    'username' => $data_json->username,
                    'img' => 'http://' . $_SERVER['SERVER_NAME'] . '/uploads/image/' . $image,
                    'nickname' => $data_json->nickname,
                    'sex' => $data_json->sex,
                    'introduce' => $data_json->introduce,
                ];
            } else {
                $data = [
                    'username' => $data_json->username,
                    'nickname' => $data_json->nickname,
                    'sex' => $data_json->sex,
                    'introduce' => $data_json->introduce,
                ];
            }
            $userModel = new UserModel();
            $data_json->uid = intval($data_json->uid);
            $userModel->updateUserData($data_json->uid, $data);
            $res = $userModel->getUserAllInfoById($data_json->uid);

            if (!$res['img']) {
                $default_head = Config::get('DEFAULT_HEAD');
                $res['img'] = $default_head;
            }
            return successEncrypt($res);
        } else {
            return errorEncrypt();
        }
    }


    /**
     * @SWG\Get(
     *     path="/index/Index/upgrade",
     *     tags={"升级接口"},
     *     summary="升级接口",
     *     description="返回渠道升级信息",
     *     @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *升级
     */
    public function upgrade()
    {
        $request = Request::instance()->get();
        if (!isset($request['channel']) || $request['channel'] == "") {
            $request['channel'] = 'cc1';
        }
        $channel_id = $this->getChannelId($request['channel']);
        if ($request['versionCode']) {
            $upgradeModel = new UpgradeModel();
            $res = $upgradeModel->getUpgradeRenew($channel_id, $request['versionCode']);
            if (!$res) {
                $msg = "该渠道没有最新版本";
                return errorEncrypt($msg);
            }
            $data = [];
            foreach ($res as $k => $v) {
                $data[$k] = $v['renew'];
            }
            if (in_array("1", $data)) {
                $upgrade = 1;//强制
            } else {
                $upgrade = 0;//不强制
            }
            $info = $upgradeModel->getUpgradeInfo($channel_id);
            $info['versionForce'] = $upgrade;
            return successEncrypt($info);
        } else {
            return errorEncrypt();
        }
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/balance",
     *   tags={"我的"},
     *   summary="用户书币余额",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *用户书币余额
     */
    public function balance()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();

        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->id)) {
            $data_json->id = intval($data_json->id);
            if ($data_json->id && $data_json->id != '-1') {
                $userModel = new UserModel();
                $res = $userModel->getUserInfoById($data_json->id);
                $data = [
                    'money' => intval($res['money']),
                    'isvip' => $res['isvip'],
                    'expire_time' => $res['expire_time'],
                ];
                goto ret;
            } else {
                $data = [
                    'money' => 0,
                    'isvip' => 0,
                    'expire_time' => 0,
                ];
                goto ret;
            }
            ret:
            return successEncrypt($data);
        }
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/consume",
     *   tags={"我的"},
     *   summary="用户章节消费记录",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *用户章节消费记录
     */
    public function consume()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->id) && isset($data_json->page)) {
            $data_json->id = intval($data_json->id);
            $user_consme = model('Index')->userConsume($data_json->id, $data_json->page);
            return successEncrypt($user_consme);
        }
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/bookConsume",
     *   tags={"我的"},
     *   summary="用户单本消费记录",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *用户单本消费记录
     */
    public function bookConsume()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->id)) {
            $data_json->id = intval($data_json->id);
            $book_consme = model('Index')->bookConsume($data_json->id);

            foreach ($book_consme as $k => $v) {
                $v['time'] = date('Y-m-d H:i:s', $v['time']);
                $v['zshu'] = $v['zshu'] * 34;
                $v['bid'] = model('Index')->book($v['bid']);
                $book_consme[$k] = $v;
            }

            return successEncrypt($book_consme);
        }
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/bindingPhone",
     *   tags={"我的"},
     *   summary="绑定手机号",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *绑定手机号
     */
    public function bindingPhone()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        $userModel = new UserModel();
        if (isset($data_json->id) && isset($data_json->phone) && isset($data_json->validate)) {
            $code = Cache::get($data_json->phone);
            if (!empty($code)) {
                if ($data_json->validate == $code) {
                    $data_json->id = intval($data_json->id);
                    if ($data_json->phone) {
                        $res = $userModel->getUserAllInfoById($data_json->id);
                        if ($res['phone']) {
                            return successEncrypt($res['phone']);
                        } else {
                            $update['phone'] = $data_json->phone;
                            $userModel->updateUserData($data_json->id, $update);
                            $res = $userModel->getUserAllInfoById($data_json->id);
                            return successEncrypt($res['phone']);
                        }
                    }
                    goto ret;
                }
                goto ret;
            }
            goto ret;
        }
        ret:
        return errorEncrypt();
    }

    /*
     *支付宝app支付
     */
    public function re()
    {
        $data_json = dataDecrypt();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->total_amount) && isset($data_json->id)) {
            $data_json->total_amount = 0.01; //测试1分钱
            $aop = new AopClient ();
            $aop->gatewayUrl = self::ALI_PAY_URL;
            $aop->appId = Config::get('APPID');
            $aop->rsaPrivateKey = Config::get('PRIVATEKEY');
            $aop->alipayrsaPublicKey = Config::get('PUBLICKEY');
            $aop->apiVersion = '1.0';
            $aop->postCharset = 'utf-8';
            $aop->format = 'json';
            $aop->signType = 'RSA2';
            //生成随机订单号
            $date = 'TW' . date("YmdHis");
            $arr = range(1000, 9999);
            shuffle($arr);

            $channelId = $this->getChannelId($data_json->channel);
            $total_fee = $data_json->total_amount;
            if ($total_fee == 365) { //测试 365
                $paytype = 1;
            } else {
                $paytype = 2;
            }
            $data_json->id = intval($data_json->id);
            if (isset($data_json->bookId)) {
                $bookId = $data_json->bookId;
                $noticeModel = new NoticeModel();
                $res = $noticeModel->getBookNoticeId($bookId);
                if (!empty($res)) {
                    $noticeId = $res['id'];
                } else {
                    $noticeId = 0;
                }
            } else {
                $bookId = 0;
                $noticeId = 0;
            }
            //创建订单
            $data = [
                'uid' => $data_json->id,
                'money' => $data_json->total_amount,  //$data_json->total_amount
                'type' => 1,//1表示支付宝支付2表示微信支付
                'status' => 0,
                'addtime' => time(),
                'paytype' => $paytype,//2表示普通购买支付(具体看数据库字段备注)
                'payid' => $date . $arr[0] . 'CCXIAO',
                'channel_id' => $channelId,
                'book_id' => $bookId,
                'notice_id' => $noticeId,
            ];
            $rechargeModel = new RechargeModel();
            $rechargeModel->saveData($data);
            $request = new AlipayTradeAppPayRequest();
            //异步地址传值方式
            //"http://bookapp.dtbooking.com/index/index/notify"
            $request->setNotifyUrl($_SERVER['SERVER_NAME'] . '/index/index/notify');
            $request->setBizContent("{\"out_trade_no\":\"" . $date . $arr[0] . 'CCXIAO' . "\",\"total_amount\":$data_json->total_amount,\"product_code\":\"QUICK_MSECURITY_PAY\",\"subject\":\"CC小说书币充值\",\"id\":\"$data_json->id\"}");
            $result = $aop->sdkExecute($request);
            $output = array('code' => 200, 'data' => htmlspecialchars_decode($result), 'msg' => '');
            echo json_encode($output);
        }
        return errorEncrypt();
    }

    /*
     *支付宝回调
     */
    public function notify()
    {
        $aop = new AopClient();
        $aop->alipayrsaPublicKey = Config::get('PUBLICKEY');
        //此处验签方式必须与下单时的签名方式一致
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        if ($flag) {

            $order_sn = $_POST['out_trade_no'];//商户订单号
            if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                if (stripos($order_sn, 'CCXIAO') !== false) {

                    //返回值写入log日志
                    $file = fopen('./payLog.txt', 'a+');
                    $data['trade_status'] = 'TRADE_SUCCESS';
                    $data['order_sn'] = $order_sn;
                    $data['pay_time'] = date('Y-m-d H:i:s', time());
                    fwrite($file, var_export($data, true));

                    $rechargeModel = new RechargeModel();
                    $userModel = new UserModel();
                    $orderStatus = $rechargeModel->getOrderStatus($order_sn);

                    if ($orderStatus['status'] == 0) {
                        $updateData['status'] = 1;
                        $updateData['paytime'] = time();
                        $res = $rechargeModel->updateOrderInfo($order_sn, $updateData);
                        if ($res !== false) {
                            $order = $rechargeModel->getOrderInfo($order_sn);
                            $uid = $order['uid'];
                            $money = getMoney($order['money']);
                            if ($order['money'] == '365') { //测试 365
                                //处理年费会员等级
                                $this->changeVipUserLevel($uid);
                            } else {
                                //处理非年费会员余额
                                $userModel->noVipMoneyInc($uid, $money);
                            }
                            echo "success";
                        } else {
                            //订单已完成支付或充值失败
                            echo "fail";
                        }
                    }
                    echo "success";
                }
            }
        }
        echo 'success';
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/rechargeLog",
     *   tags={"我的"},
     *   summary="用户充值记录",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *用户充值记录
     */
    public function rechargeLog()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        if (isset($data_json->id)) {
            $data_json->id = intval($data_json->id);
            $rechargeModel = new RechargeModel();
            $res = $rechargeModel->getRecodeLog($data_json->id);
            foreach ($res as $k => $v) {
                $v['paytime'] = date('Y-m-d h:i:s', $v['paytime']);
                $res[$k] = $v;
            }
            return successEncrypt($res);
        }
        return errorEncrypt();
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/land",
     *   tags={"登陆注册"},
     *   summary="三方登陆",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /*
     *三方登陆
     */
    public function land()
    {
        $data_json = dataDecrypt();
        // $data_json = jsonData();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }
        $userModel = new UserModel();
        if (isset($data_json->user_id) && isset($data_json->user_nickname) && isset($data_json->user_img) && isset($data_json->user_sex) && isset($data_json->user_username)) {
            if ($data_json->user_id) {
                $res = $userModel->getUserInfoByOpenid($data_json->user_id);
                if ($res) {
                    //表示有这个用户
                    return successEncrypt($res);
                } else {
                    //表示没有这个用户
                    $channelId = $this->getChannelId($data_json->channel);
                    $data = [];
                    $data = [
                        'username' => $data_json->user_username,
                        'img' => $data_json->user_img,
                        'nickname' => $data_json->user_nickname,
                        'sex' => $data_json->user_sex,
                        'open_id' => $data_json->user_id,
                        'channel' => $data_json->channel,
                        'channel_id' => $channelId,
                        'create_time' => time(),
                        'logintype' => intval(@$data_json->type),  //type类型 2：微信；3：QQ；4:微博；
                    ];

                    $user = $userModel->saveInfo($data);
                    if ($user) {
                        $res = $userModel->getUserInfoByOpenid($data_json->user_id);
                        return successEncrypt($res);
                    } else {
                        $msg = "注册失败";
                        return errorEncrypt($msg);
                    }
                }
            }
            $msg = "登陆失败";
            return errorEncrypt($msg);
        }
        return errorEncrypt();
    }

    /*
     *微博授权回调地址
     */
    public function weibo()
    {
    }


    /*
     *微信app支付入口
     */
    public function wx_pay()
    {
        $data_json = dataDecrypt();
        if (empty($data_json)) {
            return errorEncrypt();
        }
        if (!isset($data_json->channel) || $data_json->channel == "") {
            $data_json->channel = 'cc1';
        }

        if (isset($data_json->id) && isset($data_json->amount) && isset($data_json->ip)) {
            $data_json->amount = 1;  //测试1分钱
            $out_trade_no = 'TW' . date("YmdHis") . mt_rand(10000, 99999) . 'wx';
            $nonce_str = rand_code();        //调用随机字符串生成方法获取随机字符串
            $data['appid'] = Config::get('APP_ID');   //appid
            $data['mch_id'] = Config::get('MCH_ID');        //商户号
            $data['body'] = "滕文小说书币充值";
            $data['spbill_create_ip'] = $data_json->ip;   //ip地址
            $data['total_fee'] = $data_json->amount;   //  $data_json->amount                   //金额
            $data['out_trade_no'] = $out_trade_no;    //商户订单号,不能重复
            $data['nonce_str'] = $nonce_str;//随机字符串
            $data['notify_url'] = $_SERVER['SERVER_NAME'] . '/index/index/wx_notify';   //回调地址,用户接收支付后的通知,必须为能直接访问的网址,不能跟参数
            $data['trade_type'] = 'APP';      //支付方式
            //将参与签名的数据保存到数组  注意：以上几个参数是追加到$data中的，$data中应该同时包含开发文档中要求必填的剔除sign以外的所有数据
            $data['sign'] = getSign($data);        //获取签名
            $xml = ToXml($data);            //数组转xml
            $channelId = $this->getChannelId($data_json->channel);
            $total_fee = $data['total_fee'] / 100;  //$data['total_fee']/100
            if ($total_fee == 365) {  //调试 365
                $paytype = 1;
            } else {
                $paytype = 2;
            }
            //创建订单
            $data_json->id = intval($data_json->id);
            if (isset($data_json->bookId)) {
                $bookId = $data_json->bookId;
                $noticeModel = new NoticeModel();
                $res = $noticeModel->getBookNoticeId($bookId);
                if (!empty($res)) {
                    $noticeId = $res['id'];
                } else {
                    $noticeId = 0;
                }
            } else {
                $bookId = 0;
                $noticeId = 0;
            }
            $data = [
                'uid' => $data_json->id,
                'money' => $data['total_fee'] / 100,  //$data['total_fee'] / 100
                'type' => 2,//1表示支付宝支付2表示微信支付
                'status' => 0,
                'addtime' => time(),
                'paytype' => $paytype,//1：vip年费；2：表示普通购买支付(具体看数据库字段备注)
                'payid' => $data['out_trade_no'],
                'channel_id' => $channelId,
                'book_id' => $bookId,
                'notice_id' => $noticeId,
            ];
            $rechargeModel = new RechargeModel();
            $rechargeModel->saveData($data);
            //curl 传递给微信方
            $url = self::WX_PAY_URL;
            //返回结果
            $data = curl_http($url, $xml);
            if ($data) {

                //返回成功,将xml数据转换为数组.
                $re = FromXml($data);
                if ($re['return_code'] != 'SUCCESS') {
                    $output = array('code' => 201, 'data' => '', 'msg' => '签名失败');
                    return json_encode($output);
                } else {
                    //接收微信返回的数据,传给APP!
                    $arr = array(
                        'prepayid' => $re['prepay_id'],
                        'appid' => Config::get('APP_ID'),
                        'partnerid' => Config::get('MCH_ID'),
                        'package' => 'Sign=WXPay',
                        'noncestr' => $nonce_str,
                        'timestamp' => time(),
                    );
                    //第二次生成签名
                    $sign = getSign($arr);
                    $arr['sign'] = $sign;
                    $output = array('code' => 200, 'data' => $arr, 'msg' => '签名成功');
                    return json_encode($output);
                }
            }
            $msg = '请求出错';
            return errorEncrypt($msg);
        }
        return errorEncrypt();
    }

    // 微信支付回调
    public function wx_notify()
    {
        //接收微信返回的数据数据,返回的xml格式
        $xmlData = file_get_contents('php://input');

        //将xml格式转换为数组
        $data = FromXml($xmlData);

        //为了防止假数据，验证签名是否和返回的一样。
        //记录一下，返回回来的签名，生成签名的时候，必须剔除sign字段。
        $sign = $data['sign'];
        unset($data['sign']);
        if ($sign == getSign($data)) {
            //签名验证成功后，判断返回微信返回的
            if ($data['result_code'] == 'SUCCESS') {

                //用日志记录检查数据是否接受成功，验证成功一次之后，可删除。
                $file = fopen('./payLog.txt', 'a+');
                $data['pay_time'] = date('Y-m-d H:i:s', time());
                fwrite($file, var_export($data, true));

                $userModel = new UserModel();
                $rechargeModel = new RechargeModel();
                $rechargeinfo = $rechargeModel->getOrderStatus($data['out_trade_no']);

                if ($rechargeinfo['status'] == 0) {
                    //根据返回的订单号做业务逻辑
                    $arr = array(
                        'status' => 1,
                        'paytime' => time()
                    );
                    $re = $rechargeModel->updateOrderInfo($data['out_trade_no'], $arr);

                    //处理完成之后，告诉微信成功结果！
                    if ($re) {
                        $order = $rechargeModel->getOrderInfo($data['out_trade_no']);
                        $uid = $order['uid'];
                        $money = getMoney($order['money']);
                        if ($order['money'] == '365') {  //365
                            //处理年费会员等级
                            $this->changeVipUserLevel($uid);
                        } else {
                            //处理非年费会员余额
                            $userModel->noVipMoneyInc($uid, $money);
                        }
                    }
                }
                echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
            } //支付失败，输出错误信息
            else {
                $file = fopen('./payLog.txt', 'a+');
                fwrite($file, "错误信息：" . $data['return_msg'] . date("Y-m-d H:i:s"), time() . "\r\n");
            }
        } else {
            $file = fopen('./payLog.txt', 'a+');
            fwrite($file, "错误信息：签名验证失败" . date("Y-m-d H:i:s"), time() . "\r\n");
        }
    }

    /**
     * @SWG\Post(
     *   path="/index/Index/verification",
     *   tags={"登陆注册"},
     *   summary="发送验证码",
     *   description="返回首页数据",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    /**
     * 发送验证码
     */
    public function verification()
    {
        // 验证发送短信(SendSms)接口
        $res = rand(1000, 9999);
        $phone = Request::instance()->post('phone');
        $phone = trim($phone);
        $checkRes = checkTel($phone);
        if ($checkRes) {
            try {
                Cache::set($phone, $res, 300);
                $foo = new sendSms();
                $res = $foo->sendYzm($res, $phone);
                $data = $res->Code;
                if ($data == 'OK') {
                    $output = array('code' => 200, 'data' => '', 'msg' => '');
                    echo json_encode($output);
                } else {
                    $output = array('code' => 404, 'data' => '', 'msg' => '验证码发送失败');
                    echo json_encode($output);
                }
            } catch (Exception $e) {
                $output = array('code' => 404, 'data' => '', 'msg' => '验证码发送失败');
                echo json_encode($output);
            }

        } else {
            $output = array('code' => 404, 'data' => '', 'msg' => '手机号格式错误');
            echo json_encode($output);
        }
    }

    /**
     * 用户充值金额为365元时
     * 更新会员等级及会员状态
     */
    private function ChangeVipUserLevel($uid)
    {
        $userModel = new UserModel();
        $userinfo = $userModel->getUserInfoById($uid);
        if ($userinfo['expire_time'] > time()) {
            $expire_time = strtotime('+1 year', $userinfo['expire_time']);
        } else if ($userinfo['expire_time'] <= time() || $userinfo['expire_time'] == 0) {
            $expire_time = strtotime('+1 year');
        }
        $data = [
            'expire_time' => ['exp', $expire_time],
            'isvip' => ['exp', 'isvip+1'],
        ];
        $userModel->updateUserData($uid, $data);
    }

    /**
     * 检测会员过期
     */
    public function checkVipOver($data_json)
    {
        if (isset($data_json)) {
            if (isset($data_json->id)) {
                $data_json->id = intval($data_json->id);
                $userModel = new UserModel();
                $userinfo = $userModel->getUserInfoById($data_json->id);
                if (!empty($userinfo)) {
                    if ($userinfo['expire_time'] <= time()) {
                        //已过期
                        $data = ['isvip' => 0, 'expire_time' => 0];
                        $userModel->updateUserData($data_json->id, $data);
                    }
                }
            }
        }
    }

    /**
     * 获取渠道ID
     */
    public function getChannelId($data)
    {
        $channelModel = new ChannelModel();
        $channelinfo = $channelModel->getChannelIdByChannel($data);
        $res = $channelinfo['id'] ? $channelinfo['id'] : 0;
        return $res;
    }

}
