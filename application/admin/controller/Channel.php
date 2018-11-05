<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/8/30
 * Time: 11:45
 */

namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;
use think\Request;
use think\Session;
use think\Loader;
use app\admin\model\Channel as ChannelModel;
use app\admin\model\Recharge as RechargeModel;
use app\admin\model\Chapter as ChapterModel;
use app\admin\model\Book as BookModel;
use PHPExcel_IOFactory;
use PHPExcel;


class Channel extends Base
{

    /**
     * 统计
     */
    public function statistics()
    {
        $channelid = input('channelid/d');
        if (!isset($channelid) || $channelid == 0) {
            $where = [];
            $this->assign('channelid', 0);
        } else {
            $where['id'] = $channelid;
            $this->assign('channelid', $channelid);
        }

        $channelModel = new ChannelModel();
        $RechargeModel = new RechargeModel();
        $channellist = $channelModel->getChannelLists(0);  //下拉选项数据
        $list = $channelModel->getChannelLists(1, $where); //页面数据列表

        //下拉渠道选项
        $channel_list = $list->toArray();

        foreach ($channel_list['data'] as $key => $value) {
            $channelArticle = $channelModel->getChannelArticle($value['id']);
            $RechargeMoney = 0;
            $NoRechargeMoney = 0;
            $RechargeNumber = 0;
            $NoRechargeNumber = 0;
            $rechargeList = $RechargeModel->lists($value['id']);

            foreach ($rechargeList as $val) {
                if ($val['status'] == 1) {
                    $RechargeMoney += $val['money'];
                    $RechargeNumber += 1;
                } else {
                    $NoRechargeMoney += $val['money'];
                    $NoRechargeNumber += 1;
                }
            }

            $channel_list['data'][$key] = [
                'channel_id' => $value['id'],
                'channel' => $value['channel'],
                'channel_name' => $value['channel_name'],
                'channel_address' => $value['channel_address'],
                'btitle' => $channelArticle['btitle'],
                'chtitle' => $channelArticle['chtitle'],
                'RechargeMoney' => $RechargeMoney,
                'RechargeNumber' => $RechargeNumber,
                'NoRechargeMoney' => $NoRechargeMoney,
                'NoRechargeNumber' => $NoRechargeNumber,
            ];
        }
        //渠道支付统计列表

        /* 今日 昨日 本月 累计 数据统计 */
        // 今天
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $endToday = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;

        // 昨天
        $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        $endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;

        // 本月
        $beginThismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $endThismonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));

        $whereToday['addtime'] = ['between', [$beginToday, $endToday]];
        $whereYestday['addtime'] = ['between', [$beginYesterday, $endYesterday]];
        $whereMonth['addtime'] = ['between', [$beginThismonth, $endThismonth]];

        $today = $RechargeModel->getLists($whereToday);
        $yesterday = $RechargeModel->getLists($whereYestday);
        $month = $RechargeModel->getLists($whereMonth);
        $total = $RechargeModel->getLists();

        $stats = [
            'today' => statsData($today),
            'yesterday' => statsData($yesterday),
            'month' => statsData($month),
            'total' => statsData($total),
        ];

        $page = $list->render();
        $this->assign('page', $page);
        $this->assign('stats', $stats);
        $this->assign('channellist', $channellist);
        $this->assign('channel_list', $channel_list['data']);
        return $this->fetch();
    }

    /**
     * 统计渠道详情
     */
    public function statisticeDetail()
    {
        $paystatus = input('paystatus/d'); ////支付状态
        $paytype = input('paytype/d'); //支付类型
        $type = input('type/d'); //支付方式
        $starttime = input('starttime/s');
        $endtime = input('endtime/s');
        $channel_id = input('channel_id/d');

        $params = $this->paramsWhere($paystatus, $paytype, $type, $starttime, $endtime);

        $this->assign('paystatus', $params['data']['paystatus']);
        $this->assign('paytype', $params['data']['paytype']);
        $this->assign('type', $params['data']['type']);
        $this->assign('starttime', $params['data']['starttime']);
        $this->assign('endtime', $params['data']['endtime']);
        //渠道
        $where['re.channel_id'] = $channel_id;
        $RechargeModel = new RechargeModel();
        $data = $RechargeModel->detailLists($params['map'], $where);

        //总计充值金额计算
        $dataCount = $RechargeModel->detailLists($params['map'], $where, 1);

        $CountData = statsData($dataCount);

        $page = $data->render();
        $this->assign('page', $page);
        $this->assign('count_data', $CountData['total_count']);
        $this->assign('channel_id', $channel_id);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 订单列表
     * @return mixed
     */
    public function order()
    {
        $paystatus = input('paystatus/d', 0, 'intval'); ////支付状态
        $paytype = input('paytype/d', 0, 'intval'); //支付类型
        $type = input('type/d', 0, 'intval'); //支付方式
        $starttime = input('starttime/s', '', 'trim');
        $endtime = input('endtime/s', '', 'trim');
        $channel_id = input('channel_id/d', 0, 'intval');

        $params = $this->paramsWhere($paystatus, $paytype, $type, $starttime, $endtime);

        $this->assign('paystatus', $params['data']['paystatus']);
        $this->assign('paytype', $params['data']['paytype']);
        $this->assign('type', $params['data']['type']);
        $this->assign('starttime', $params['data']['starttime']);
        $this->assign('endtime', $params['data']['endtime']);

        $RechargeModel = new RechargeModel();

        $data = $RechargeModel->detailLists($params['map']);
        $page = $data->render();
        $this->assign('page', $page);
        $this->assign('channel_id', $channel_id);

        $this->assign('data', $data);
        return $this->fetch();
    }

    public function paramsWhere($paystatus, $paytype, $type, $starttime, $endtime)
    {
        $paramsData = array();
        $params = array();
        $data = array();

        //支付状态
        if (!isset($paystatus) || $paystatus == 0) {
            $paystatus = 0;
        } else {
            if ($paystatus == 1) {
                $params['re.status'] = 0;
            } else if ($paystatus == 2) {
                $params['re.status'] = 1;
            }
        }
        //支付类型
        if (!isset($paytype) || $paytype == 0) {
            $paytype = 0;
        } else {
            $params['re.paytype'] = $paytype;
        }
        //支付方式
        if (!isset($type) || $type == 0) {
            $type = 0;
        } else {
            $params['re.type'] = $type;
        }

        //查询时间
        if ($starttime != "" && $endtime != "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $end_time = strtotime($endtime . ' 23:59:59');
            $params['re.addtime'] = ['between', [$start_time, $end_time]];
        } else if ($starttime != "" && $endtime == "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $params['re.addtime'] = ['>', $start_time];
            $endtime = "";
        } else if ($starttime == "" && $endtime != "") {
            $end_time = strtotime($endtime . ' 23:59:59');
            $params['re.addtime'] = ['<', $end_time];
            $starttime = "";
        } else {
            $starttime = "";
            $endtime = "";
        }

        $data = [
            'paystatus' => $paystatus,
            'paytype' => $paytype,
            'type' => $type,
            'starttime' => $starttime,
            'endtime' => $endtime
        ];
        $paramsData = [
            'map' => $params,
            'data' => $data
        ];
        return $paramsData;
    }

    /**
     * 导出列表
     * @param  viod
     */
    public function exportData()
    {
        //导出条件
        $paystatus = input('paystatus/d', 0, 'intval'); ////支付状态
        $paytype = input('paytype/d', 0, 'intval'); //支付类型
        $type = input('type/d', 0, 'intval'); //支付方式
        $starttime = input('starttime/s', '', 'trim');
        $endtime = input('endtime/s', '', 'trim');

        $params = $this->paramsWhere($paystatus, $paytype, $type, $starttime, $endtime);
        $RechargeModel = new RechargeModel();
        $data = $RechargeModel->detailLists($params['map'], 1);

        $title = '订单列表' . date('YmdHis', time());  //定义文件名

        vendor("PHPExcel.PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");

        $objPHPExcel = new \PHPExcel();

        // 表头
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '订单类型')
            ->setCellValue('B1', '用户名称')
            ->setCellValue('C1', '充值金额(元)')
            ->setCellValue('D1', '充值方式')
            ->setCellValue('E1', '充值状态')
            ->setCellValue('F1', '充值时间');

        foreach ($data as $k => $v) {
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($k + 2), getOrderType($v['paytype']));
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($k + 2), $v['username']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($k + 2), $v['money']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($k + 2), getOrderPayMothod($v['type']));
            $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($k + 2), getOrderPayStatus($v['status']));
            $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($k + 2), date('Y-m-d H:i:s', $v['addtime']));
        }

        $objPHPExcel->getActiveSheet()->setTitle($title);      //设置sheet的名称
        $objPHPExcel->setActiveSheetIndex(0);                   //设置sheet的起始位置
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');   //通过PHPExcel_IOFactory的写函数将上面数据写出来
        $PHPWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
        exit;
    }


    /**
     * 渠道列表
     */
    public function channel()
    {
        $channelModel = new ChannelModel();
        $data = $channelModel->getChannelLists(1);
        $page = $data->render();
        $this->assign('page', $page);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 添加渠道
     */
    public function addChannel()
    {
        return $this->fetch();
    }

    /**
     * 编辑渠道
     */
    public function editChannel()
    {
        $id = input('id/d');
        $channelModel = new ChannelModel();
        $info = $channelModel->channelDetail($id);
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 编辑渠道
     */
    public function editChannelAddress()
    {
        $id = input('id/d');
        $channelModel = new ChannelModel();
        $info = $channelModel->channelDetail($id);
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 渠道信息保存
     */
    public function saveChannel()
    {
        $request = Request::instance();
        if ($request->isPost()) {
            $channelModel = new ChannelModel();
            $channel = input('post.channel/s');
            $channel_name = input('post.channel_name/s');
            $channel_address = input('post.channel_address/s');
            $id = input('post.id/d');
            $data['channel'] = $channel;
            if ($channel_name) {
                $data['channel_name'] = $channel_name;
            }
            if ($channel_address) {
                $data['channel_address'] = $channel_address;
            }
            if (isset($id) && $id != 0) {
                //编辑渠道信息
                $data['update_time'] = time();
                $res = $channelModel->editChannelInfo($id, $data);
                if ($res !== false) {
                    $this->success('编辑成功', 'Channel/channel');
                } else {
                    $this->error('编辑失败');
                }
            } else {
                //添加渠道信息
                $data['create_time'] = time();
                $addID = $channelModel->addChannelInfo($data);
                if ($addID) {
                    $this->success('添加成功', 'Channel/channel');
                } else {
                    $this->error('添加失败');
                }
            }
        }

    }

    /**
     * 移除渠道
     */
    public function delChannel()
    {
        $id = input('id/d');
        $data['is_delete'] = 1;
        if (isset($id) && $id != 0) {
            //编辑渠道信息
            $channelModel = new ChannelModel();
            $res = $channelModel->delChannelStatus($id, $data);
            if ($res !== false) {
                $this->success('删除成功', 'Channel/channel');
            } else {
                $this->error('删除失败');
            }
        }
    }


    /**
     * 渠道文章
     */
    public function channelArticle()
    {
        $channelModel = new ChannelModel();
        $data = $channelModel->channelArticleList();
        $page = $data->render();
        $this->assign('page', $page);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 编辑功能
     * （通过渠道第一次打开的章节）
     */
    public function editArticle()
    {
        $id = input('id/d');
        $channelModel = new ChannelModel();
        $channelInfo = $channelModel->getChannelDetail($id);
        if ($channelInfo['bid'] != 0) {
            //书籍存在
            if ($channelInfo['zid'] != 0) {
                //章节存在
                $chapterModel = new ChapterModel();
                $chapterlist = $chapterModel->getChapterList($channelInfo['bid']);
                $this->assign('chapterlist', $chapterlist);
                $this->assign('chapterid', $channelInfo['zid']);
            }
            $this->assign('bookid', $channelInfo['bid']);
        } else {
            $this->assign('bookid', 0);
        }
        $bookModel = new BookModel();
        $booklist = $bookModel->booklists(0);
        $this->assign('booklist', $booklist);
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     * 根据bookid查询下面的所有章节
     */
    public function getChapterList()
    {
        $request = Request::instance();
        if ($request->isPost()) {
            $bookid = input('post.bookid/d');
            $chapterModel = new ChapterModel();
            $chapterlist = $chapterModel->getChapterList($bookid);
            return json_encode($chapterlist);
        }
    }

    /**
     * 保存渠道打开的章节信息
     */
    public function saveChapter()
    {
        $request = Request::instance();
        if ($request->isPost()) {
            $id = input('post.id/d');
            $bookid = input('post.bookid/d', 0);
            $chapterid = input('post.chapterid/d', 0);

            if ($bookid != 0 && $chapterid == 0) {
                $this->error('请选择章节内容');
            } else {
                $data = [
                    'bid' => $bookid,
                    'zid' => $chapterid,
                ];
                $channelModel = new ChannelModel();
                $res = $channelModel->editChannelInfo($id, $data);
                if ($res !== false) {
                    $this->success('编辑成功', 'Channel/channelArticle');
                } else {
                    $this->error('编辑失败');
                }
            }
        }
    }


    /**
     * 章节字符替换
     */
    public function replace()
    {
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $title = $data['title'];
            $bid = $data['bid'];
            $name = $data['name'];
            $sql = "UPDATE ien_chapter SET content = replace(content,'{$title}','{$name}') WHERE bid = '{$bid}'";
            $res = Db::execute($sql);
            if ($res) {
                $this->success("字符替换完成！");
            } else {
                $this->error("字符替换出错请重试！");
            }

        } else {
            return $this->fetch();
        }
    }


}