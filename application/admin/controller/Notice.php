<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/10/8
 * Time: 15:23
 */

namespace app\admin\controller;

use think\Db;
use sdk\androidNotice;
use app\admin\model\Notice as NoticeModel;
use app\admin\model\Channel as ChannelModel;
use app\admin\model\Recharge as RechargeModel;

class Notice extends Base
{
    /**
     * 推送信息列表
     * @return mixed
     */
    public function index()
    {
        $starttime = input('starttime/s');
        $endtime = input('endtime/s');
        $where = [];
        //查询时间
        if ($starttime != "" && $endtime != "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $end_time = strtotime($endtime . ' 23:59:59');
            $where['n.create_time'] = ['between', [$start_time, $end_time]];
            $this->assign('starttime', $starttime);
            $this->assign('endtime', $endtime);
        } else if ($starttime != "" && $endtime == "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $where['n.create_time'] = ['>', $start_time];
            $this->assign('starttime', $starttime);
        } else if ($starttime == "" && $endtime != "") {
            $end_time = strtotime($endtime . ' 23:59:59');
            $where['n.create_time'] = ['<', $end_time];
            $this->assign('endtime', $endtime);
        } else {
            $this->assign('starttime', '');
            $this->assign('endtime', '');
        }

        $noticeModel = new NoticeModel();
        $data = $noticeModel->getNoticeLists($where);
        $pageObject = $noticeModel->getNoticePage($where);
        $dataList = $data->toArray();
        foreach ($dataList['data'] as $key => $value) {
            if ($value['trace_id'] != "") {
                $res = object_to_array($this->noticeTracer($value['trace_id']));
                foreach ($res as $k => $v) {
                    if (is_array($v)) {
                        $dataList['data'][$key]['click'] = $v['data']['data']['click'];    //点击率
                        $dataList['data'][$key]['delivered'] = $v['data']['data']['delivered'];  //到达率
                    }
                }
            } else {
                $dataList['data'][$key]['click'] = '0';    //点击率
                $dataList['data'][$key]['delivered'] = '0';  //到达率
            }
        }
        $page = $pageObject->render();
        $this->assign('page', $page);
        $this->assign('data', $dataList['data']);
        return $this->fetch();
    }

    /**
     * 查看推送充值列表
     * @return mixed
     */
    public function details()
    {
        $noticeId = input('id/d', '0', 'intval');
        $starttime = input('starttime/s');
        $endtime = input('endtime/s');
        $where = [];
        $where['re.notice_id'] = $noticeId;
        //查询时间
        if ($starttime != "" && $endtime != "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $end_time = strtotime($endtime . ' 23:59:59');
            $where['re.addtime'] = ['between', [$start_time, $end_time]];
            $this->assign('starttime', $starttime);
            $this->assign('endtime', $endtime);
        } else if ($starttime != "" && $endtime == "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $where['re.addtime'] = ['>', $start_time];
            $this->assign('starttime', $starttime);
        } else if ($starttime == "" && $endtime != "") {
            $end_time = strtotime($endtime . ' 23:59:59');
            $where['re.addtime'] = ['<', $end_time];
            $this->assign('endtime', $endtime);
        } else {
            $this->assign('starttime', '');
            $this->assign('endtime', '');
        }

        $RechargeModel = new RechargeModel();
        $data = $RechargeModel->getNoticeDetail($where);
        //总计充值金额计算
        $dataCount = $RechargeModel->getNoticeDetail($where, 1);

        $CountData = statsData($dataCount);

        $page = $data->render();
        $this->assign('page', $page);
        $this->assign('pay_count', $CountData['pay_count']);
        $this->assign('unpay_count', $CountData['unpay_count']);
        $this->assign('data', $data);
        $this->assign('noticeId', $noticeId);
        return $this->fetch();

    }

    /**
     * 导出列表
     * @param  viod
     */
    public function exportData()
    {
        //导出条件
        $noticeId = input('id/d', '0', 'intval');
        $starttime = input('starttime/s', '', 'trim');
        $endtime = input('endtime/s', '', 'trim');

        $where = [];
        $where['re.notice_id'] = $noticeId;

        //时间查询
        if ($starttime != "" && $endtime != "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $end_time = strtotime($endtime . ' 23:59:59');
            $where['re.addtime'] = ['between', [$start_time, $end_time]];
        } else if ($starttime != "" && $endtime == "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $where['re.addtime'] = ['>', $start_time];
        } else if ($starttime == "" && $endtime != "") {
            $end_time = strtotime($endtime . ' 23:59:59');
            $where['re.addtime'] = ['<', $end_time];
        }


        $RechargeModel = new RechargeModel();

        $data = $RechargeModel->getNoticeDetail($where, 1);

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

    public function noticeTracer($id)
    {
        $notice = new androidNotice();
        $tracerInfo = $notice->tracerInfo($id);
        return $tracerInfo;
    }


}