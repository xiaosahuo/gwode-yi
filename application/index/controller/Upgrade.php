<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/17
 * Time: 15:40
 */

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\admin\model\Upgrade as UpgradeModel;

class Upgrade extends Controller
{
    public function download()
    {
        $channel = input('channel/s', 'cc1', 'trim');
        $upgradeModel = new UpgradeModel();
        $upgradeList = $upgradeModel->getUpgradeInfo($channel);
        $downloadUrl = $upgradeList['address'];
        if ($downloadUrl != "") {
            $this->redirect($downloadUrl);
        }
    }
}