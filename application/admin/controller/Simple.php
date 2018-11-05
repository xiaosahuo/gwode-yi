<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/8/31
 * Time: 11:10
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Config;
use app\admin\model\Admin as AdminModel;

class Simple extends Controller
{
    /**
     * 用户登录方法
     * @param viod
     */
    public function login()
    {
        //处理登陆信息
        if (request()->isPost()) {
            //表单验证
            $username = input('post.username/s', '', 'trim');
            $password = input('post.password/s', '', 'trim');
            if (!$username) {
                $this->error('请输入账号');
            }
            if (!$password) {
                $this->error('请输入密码');
            }
            $adminModel = new AdminModel();
            $res = $adminModel->getAdminLoginInfo($username);
            if ($password == $res['password']) {
                session('uid', $res['id']);
                $data['last_login_time'] = time();
                $data['last_login_ip'] = getIP();
                $adminModel->adminLoginInfoUpdate($res['id'], $data);
                $this->redirect('Index/index');
            } else {
                $this->error('账号或密码错误');
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 退出登录
     * @param viod
     */
    public function loginOut()
    {
        //删除用户信息session
        session('uid', null);
        //重定向登陆页面
        $this->redirect('Simple/login');
    }


}