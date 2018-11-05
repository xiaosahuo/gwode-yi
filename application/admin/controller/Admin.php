<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;
use think\Session;
use app\admin\model\Admin as AdminModel;
use app\admin\model\Auth as AuthModel;


class Admin extends Base
{

    /**
     * @return mixed
     */
    public function index()
    {
        $adminModel = new AdminModel();
        $list = $adminModel->lists();
        $page = $list->render();
        $this->assign('page', $page);
        $this->assign('data', $list);
        return $this->fetch();
    }

    public function add()
    {
        $request = Request::instance();
        if ($request->isPost()) {
            $adminModel = new AdminModel();
            $username = input('post.username/s', '', 'trim');
            $password = input('post.password/s', '', 'trim');
            $info = $adminModel->getAdminInfo($username);
            if (empty($info)) {
                $this->error("账号已存在，请重新添加");
            }
            $params = [
                'username' => $username,
                'password' => encryptPassword($password),
                'role_id' => 2,
            ];
            $res = $adminModel->addAdmin($params);
            if ($res) {
                $authModel = new AuthModel();
                $data['auth_rule'] = '1';
                $data['user_id'] = $res;
                $authModel->saveAuth($data);  //默认添加首页权限
                $this->success("添加成功", 'Admin/index');
            } else {
                $this->error("添加失败");
            }
        } else {
            return $this->fetch();
        }
    }


    /**
     * 调整管理员状态
     */
    public function adminStatus()
    {
        $id = input('post.id/d', 0, 'intval');
        $status = input('post.status/d', 0, 'intval');
        $data = ['status' => $status];
        $adminModel = new AdminModel();
        $res = $adminModel->adminStatus($id, $data);
        if ($res !== false) {
            return json_encode(['code' => 1, 'data' => '', 'msg' => '调整成功']);
        } else {
            return json_encode(['code' => 2, 'data' => '', 'msg' => '调整失败']);
        }
    }

    /**
     * 管理员删除
     */
    public function del()
    {
        $id = input('id/d', 0, 'intval');
        $adminModel = new AdminModel();
        $res = $adminModel->delAdmin($id);
        if ($res !== false) {
            $this->success("删除成功", 'Admin/index');
        } else {
            $this->error("删除失败");
        }
    }

    public function auth()
    {
        $id = input('id/d', 0, 'intval');
        $authModel = new AuthModel();
        $auth_list = $authModel->lists();
        $userAuth = $authModel->getUserAuth($id);
        $userAuthList = explode(',', $userAuth['auth_rule']);

        $authList = [];
        foreach ($auth_list as $k => $v) {
            if ($v['auth_controller_name'] != "首页管理") {
                $authList[$v['auth_controller_name']][$k]['module_name'] = $v['auth_controller_name'];
                $authList[$v['auth_controller_name']][$k]['auth_id'] = $v['id'];
                $authList[$v['auth_controller_name']][$k]['action_name'] = $v['auth_action_name'];
            }
        }
        $this->assign('authlist', $authList);
        $this->assign('userauthlist', $userAuthList);
        $this->assign('user_id', $id);
        return $this->fetch();
    }

    public function saveAuth()
    {
        $authModel = new AuthModel();
        $params = [];
        $auth = input('post.auth/a');
        if (empty($auth)) {
            $this->error('请先添加权限功能');
        }
        $auth_rule = implode(',', $auth);
        $user_id = input('post.user_id/d');
        $params['auth_rule'] = '1,' . $auth_rule;
        $res = $authModel->getUserAuthId($user_id);
        if (empty($res)) {
            //添加
            $params['user_id'] = $user_id;
            $res = $authModel->saveAuth($params);
            if ($res) {
                $this->success('权限添加成功', 'Admin/index');
            } else {
                $this->error('权限添加失败');
            }
        } else {
            //更新
            $res = $authModel->modifyAuth($res['id'], $params);
            if ($res !== false) {
                $this->success('权限更新成功', 'Admin/index');
            } else {
                $this->error('权限更新失败');
            }
        }
    }


    /**
     * ajax成功返回
     * param $data array  数据数组
     * param $info string 成功返回的字符串
     * return json
     */
    protected function _success($datas = array(), $info = 'success')
    {
        $data['status'] = 1;
        $data['data'] = $datas;
        $data['info'] = $info;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }

    /**
     * ajax成功返回
     * param $info string 错误返回的字符串
     * return json
     */
    protected function _error($info = 'error')
    {
        $data['status'] = 0;
        $data['info'] = $info;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }


}