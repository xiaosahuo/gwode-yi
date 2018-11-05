<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/8/30
 * Time: 11:50
 */

namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Db;
use think\Request;
use app\admin\model\Auth as AuthModel;

class Base extends Controller
{

    protected function _initialize()
    {
        date_default_timezone_set('PRC');

        // $request = Request::instance();
        if (session('uid') === null) {
            $this->redirect('Simple/login');
        }

        $menu = $this->menuLists();
        $this->assign('menu', $menu);
        /*
        $userAuthList = $this->getUserAuth(session('uid'));
        $func['auth_module'] = strtolower($request->module());
        $func['auth_controller'] = strtolower($request->controller());
        $func['auth_action'] = strtolower($request->action());

        if(!in_array($func,$userAuthList)){
           // $this->error("无权限操作");
        }
        */
    }


    /**
     * 左侧菜单栏
     */

    public function menuLists()
    {
        $menuList = [];
        $authModel = new AuthModel();
        $menu = $authModel->getMenu();
        $list = list_to_tree($menu);
        foreach ($list as $key => $value) {
            if ($value['rule_name'] == '#') {
                $menuList[$key]['url'] = "#";
            } else {
                $menuList[$key]['url'] = $value['rule_name'];
            }
            $menuList[$key]['menu_name'] = $value['menu_name'];
            if (isset($value['listArea'])) {
                foreach ($value['listArea'] as $k => $v) {
                    $menuList[$key]['listArea'][$k]['url'] = $v['rule_name'];
                    $menuList[$key]['listArea'][$k]['menu_name'] = $v['menu_name'];
                }
            }
        }
        return $menuList;
    }

    /**
     * @param $uid
     * @return array|false|mixed|null|\PDOStatement|string|\think\Model
     */
    protected function getUserAuth($uid)
    {
        $authModel = new AuthModel();
        $userAuth = $authModel->getUserAuth($uid);
        $userAuth = explode(',', $userAuth['auth_rule']);
        return $this->getAuthUser($userAuth);
    }

    /**
     * @param $data
     */
    protected function getAuthUser($data)
    {
        $auth = [];
        $authModel = new AuthModel();
        foreach ($data as $key => $value) {

            $authInfo = $authModel->getAdminAuth($value);
            $auth[$key]['auth_module'] = strtolower($authInfo['auth_module']);
            $auth[$key]['auth_controller'] = strtolower($authInfo['auth_controller']);
            $auth[$key]['auth_action'] = strtolower($authInfo['auth_action']);
        }
        return $auth;
    }

}