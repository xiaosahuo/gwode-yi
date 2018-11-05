<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/8/31
 * Time: 9:26
 */

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Db;
use think\Request;
use think\Session;
use think\Loader;
use app\admin\model\User as UserModel;
use app\admin\model\Channel as ChannelModel;


class User extends Base
{

    /**
     * 用户统计
     * @return mixed
     */
    public function statistics()
    {
        $channel_id = input('channel_id/d', '0', 'intval'); // 渠道
        $phone = input('phone/s', '', 'trim');              // 手机号
        $logintype = input('logintype/d', '0', 'intval');   // 登录方式 （1：非三方登录；2：三方登录；3：手机号；4：微信；5：QQ；6：微博；）
        $starttime = input('starttime/s', '', 'trim');      // 开始时间
        $endtime = input('endtime/s', '', 'trim');          // 结束时间
        $where = [];

        /* 查询条件整理 */
        // 渠道
        if (!isset($channel_id) || $channel_id == 0) {
            $this->assign('channel_id', 0);
        } else {
            $where['u.channel_id'] = $channel_id;
            $this->assign('channel_id', $channel_id);
        }

        //登录方式
        if (!isset($logintype) || $logintype == 0) {
            $this->assign('logintype', 0);
        } else {
            switch ($logintype) {
                case '1':  //非三方登录
                    $where['u.open_id'] = ['exp', 'is null'];
                    break;
                case '2':  //三方登录
                    $where['u.open_id'] = ['exp', 'is not null'];
                    break;
                case '3':  //手机号登录
                    $where['u.logintype'] = 1;
                    break;
                case '4':  //微信登录
                    $where['u.logintype'] = 2;
                    break;
                case '5':  //QQ登录
                    $where['u.logintype'] = 3;
                    break;
                case '6':  //微博登录
                    $where['u.logintype'] = 4;
                    break;
                default:
                    break;
            }
            $this->assign('logintype', $logintype);
        }

        //手机号
        if ($phone == "") {
            $this->assign('phone', '');
        } else {
            $where['u.phone'] = ['like', $phone . '%'];
            $this->assign('phone', $phone);
        }

        //查询时间
        if ($starttime != "" && $endtime != "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $end_time = strtotime($endtime . ' 23:59:59');
            $where['u.create_time'] = ['between', [$start_time, $end_time]];
            $this->assign('starttime', $starttime);
            $this->assign('endtime', $endtime);
        } else if ($starttime != "" && $endtime == "") {
            $start_time = strtotime($starttime . ' 00:00:00');
            $where['u.create_time'] = ['>', $start_time];
            $this->assign('starttime', $starttime);
        } else if ($starttime == "" && $endtime != "") {
            $end_time = strtotime($endtime . ' 23:59:59');
            $where['u.create_time'] = ['<', $end_time];
            $this->assign('endtime', $endtime);
        } else {
            $this->assign('starttime', '');
            $this->assign('endtime', '');
        }

        $userModel = new UserModel();
        $channelModel = new ChannelModel();
        $channellist = $channelModel->getChannelLists(0);  //下拉选项数据
        $list = $userModel->getUserLists($where); //页面数据列表

        //用户统计列表
        $page = $list->render();
        $this->assign('page', $page);
        $this->assign('channellist', $channellist);
        $this->assign('list', $list);
        return $this->fetch();
    }

}