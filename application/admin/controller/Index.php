<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use sdk\androidNotice;
use ftp\sftp;
use think\Db;
use think\Request;
use think\Session;
use think\Loader;
use think\Config;
use app\user\api\UserApi as UserApi;
use org\Verify as Verify;
use app\user\common\Encrypt as Encrypt;
use think\Hook;
use app\admin\api\AdsApi as AdsApi;
use app\admin\model\HomePageCopy as HPCModel;
use app\admin\model\Upgrade as UpgradeModel;
use app\admin\model\Channel as ChannelModel;
use app\admin\model\Notice as NoticeModel;


class Index extends Base
{
    public function index()
    {
        $uid = session('uid');
        $type = input('type/d', 1);  //1：男生精选； 2：女生精选；3：本周精选男；4：本周精选女
        if ($uid) {
            $book = Loader::model('Index')->bookList();
            $hpcModel = new HPCModel();
            $res = $hpcModel->lists(1, $type);
            $page = $res->render();
            $this->assign('page', $page);
            $this->assign('book', $book);
            $this->assign('res', $res);
            $this->assign('state', 'index');

            if ($type == 2) {
                return $this->fetch('index2');
            } else if ($type == 3) {
                return $this->fetch('index3');
            } else if ($type == 4) {
                return $this->fetch('index4');
            } else {
                return $this->fetch();
            }

        } else {
            $this->redirect('Simple/login');
        }

    }


    public function res()
    {
        $wz = $_POST['seat'];
        $data['bookid'] = $_POST['bookid'];
        $hpcModel = new HPCModel();
        $res = $hpcModel->wzModify($wz, $data);
        echo $res;
    }

    /*
     *书架默认管理
     */
    public function bookshelf()
    {
        $uid = session('uid');
        if ($uid) {
            $book = Loader::model('Index')->bookList();
            $hpcModel = new HPCModel();
            $res = $hpcModel->lists(2);
            $page = $res->render();
            $this->assign('page', $page);
            $this->assign('book', $book);
            $this->assign('res', $res);
            $this->assign('state', 'index');
            return $this->fetch();
        } else {
            return $this->fetch('login');
        }
    }

    /*
     *书库管理
     */
    public function bookstore()
    {
        $uid = session('uid');
        if ($uid) {
            $book = Loader::model('Index')->bookList();
            $hpcModel = new HPCModel();
            $res = $hpcModel->lists(3);
            $page = $res->render();
            $this->assign('page', $page);
            $this->assign('book', $book);
            $this->assign('res', $res);
            $this->assign('state', 'index');
            return $this->fetch();
        } else {
            return $this->fetch('login');
        }
    }

    /*
     *排行管理
     */
    public function rankings()
    {
        $uid = session('uid');
        if ($uid) {
            $book = Loader::model('Index')->bookList();
            $hpcModel = new HPCModel();
            $res = $hpcModel->lists(4);
            $page = $res->render();
            $this->assign('page', $page);
            $this->assign('book', $book);
            $this->assign('res', $res);
            $this->assign('state', 'index');
            return $this->fetch();
        } else {
            return $this->fetch('login');
        }
    }

    /*
     *男女默认管理
     */
    public function dif()
    {
        $uid = session('uid');
        if ($uid) {
            $book = Loader::model('Index')->bookList();
            $hpcModel = new HPCModel();
            $res = $hpcModel->lists(5);
            $page = $res->render();
            $this->assign('page', $page);
            $this->assign('book', $book);
            $this->assign('res', $res);
            $this->assign('state', 'index');
            return $this->fetch();
        } else {
            return $this->fetch('login');
        }
    }

    /*
     *关键字管理
     */
    public function keyword()
    {
        $uid = session('uid');
        if ($uid) {
            $book = Loader::model('Index')->bookList();
            $hpcModel = new HPCModel();
            $res = $hpcModel->lists(6);
            $page = $res->render();
            $this->assign('page', $page);
            $this->assign('book', $book);
            $this->assign('res', $res);
            $this->assign('state', 'index');
            return $this->fetch();
        } else {
            return $this->fetch('login');
        }
    }

    /*
     *关键字修改
     */
    public function keywordEdit()
    {
        $id = input('post.id');
        $data['bookid'] = input('post.bookid/d');
        $data['remark'] = input('post.remark/s');
        $hpcModel = new HPCModel();
        $res = $hpcModel->modify($id, $data);
        echo $res;
    }

    /*
     *文字描述添加
     */
    public function add()
    {
        if (Request::instance()->isPost()) {
            $data = [
                'remark' => input('post.remark/s'),
                'bookid' => input('post.bookid/d'),
                'type' => 6
            ];

            $hpcModel = new HPCModel();
            $res = $hpcModel->addData($data);
            if ($res) {
                $this->redirect('admin/index/Keyword');
            } else {
                $this->redirect('admin/index/add');
            }
        }
        $book = Loader::model('Index')->bookList();
        $this->assign('state', 'index');
        $this->assign('book', $book);
        return $this->fetch();
    }

    /*
     *删除操作
     */
    public function delete()
    {
        $id = input('post.id/d');
        $hpcModel = new HPCModel();
        $res = $hpcModel->deleteData($id);
        echo $res;
    }

    /*
     *升级操作
     */
    public function upgrade()
    {
        $uid = session('uid');
        if ($uid) {
            $upgradeModel = new UpgradeModel();
            $data = $upgradeModel->lists();
            $page = $data->render();
            $this->assign('page', $page);
            $this->assign('res', $data);
            return $this->fetch();
        } else {
            return $this->fetch('login');
        }
    }

    /**
     * @return string
     */
    public function upgradeStatus()
    {
        $id = input('post.id/d');
        $status = input('post.status/d');
        $data = ['status' => $status];
        $upgradeModel = new UpgradeModel();
        $res = $upgradeModel->upgradeStatus($id, $data);
        if ($res !== false) {
            return json_encode(['code' => 0, 'data' => '', 'msg' => '调整成功']);
        } else {
            return json_encode(['code' => 1, 'data' => '', 'msg' => '调整失败']);
        }
    }

    /*
     *强制操作
     */
    public function force()
    {
        $request = Request::instance()->post();
        $upgradeModel = new UpgradeModel();
        $count = $upgradeModel->details($request['id']);
        if ($count['renew'] == 0) {
            $data['renew'] = 1;
            $res = $upgradeModel->modifyData($request['id'], $data);
        } else {
            $data['renew'] = 0;
            $res = $upgradeModel->modifyData($request['id'], $data);
        }
        if ($res) {
            echo 1;
            die;
        } else {
            echo 2;
            die;
        }

    }

    /*
     * 升级数据修改
     */
    public function upgradeAlter()
    {
        $upgradeModel = new UpgradeModel();
        $id = input('post.id/d');
        $data['address'] = input('post.address/s');
        $data['content'] = input('post.content/s');
        $data['version'] = input('post.version/s');
        $res = $upgradeModel->modifyData($id, $data);
        echo $res;
    }

    /*
     * 升级删除操作
     */
    public function upgradeDelete()
    {
        $id = input('post.id/d');
        $upgradeModel = new UpgradeModel();
        $res = $upgradeModel->del($id);
        echo $res;
    }

    /**
     * 升级包上传
     * @return string
     */
    public function uploadApk()
    {
        if (Request::instance()->isPost()) {
            $file = request()->file('apk');
            $channelId = input('post.channelId');
            if (empty($file)) {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '请选择上传升级包文件']);
            }
            $info = $file->validate(['ext' => 'apk'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'apk');

            if ($info) {
                // 移动到框架应用根目录/public/uploads/apk/ 目录下
                $sftp = new sftp();
                $config = [
                    'host' => Config::get('sftp.host'),
                    'port' => Config::get('sftp.port'),
                    'user' => Config::get('sftp.user'),
                    'passwd' => Config::get('sftp.passwd'),
                ];

                $sftp->init($config);
                $sftp->connect();
                $remote = Config::get('remote_folder') . $info->getFilename();  //测试 /home/www/dyc-apk-download/
                $local = $_SERVER["DOCUMENT_ROOT"] . '/uploads/apk/' . $info->getSavename();
                $sftp->upload($remote, $local);

                $address = Config::get('apk_download_url') . '/' . $info->getFilename();
                $domain = Config::get('DOMAIN');
                $data = [
                    'address' => $address,
                    'channel_id' => $channelId
                ];
                $upgradeModel = new UpgradeModel();
                $upgradeModel->saveApkDownloadInfo($data);

                $apkAddress = ['apk_address' => $domain . '/index/Upgrade/downloadApk?channel_id=' . $channelId];
                return json_encode(['code' => 0, 'data' => $apkAddress, 'msg' => '上传成功']);
            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => $file->getError()]);
            }
        }
    }

    /*
     *升级添加
     */
    public function upgradeAdd()
    {
        $upgradeModel = new UpgradeModel();
        $channelModel = new ChannelModel();

        if (Request::instance()->isPost()) {
            $data = [
                'address' => input('post.address/s', '', 'trim'),
                'content' => input('post.content/s', '', 'trim'),
                'version' => input('post.version/s', '', 'trim'),
                'versionCode' => input('post.versionCode/s', '', 'trim'),
                'renew' => input('post.renew/d', 0, 'intval'),
                'channel_id' => input('post.channelid/d', 0, 'intval'),
                'status' => 0,
            ];
            $res = $upgradeModel->createData($data);
            if ($res) {
                $this->redirect('admin/index/upgrade');
            } else {
                $this->redirect('admin/index/upgradeAdd');

            }
        }
        $info = $upgradeModel->getLastVersionCode();
        $list = $channelModel->getChannelLists(0);
        $this->assign('res', $info);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /*推送消息页面*/
    public function notice()
    {
        $book = Loader::model('Index')->bookList();
        $channelModel = new ChannelModel();
        $channelList = $channelModel->getChannelLists(0);
        $token = time();
        session('token', $token, 60);
        $this->assign('token', $token);
        $this->assign('channel_list', $channelList);
        $this->assign('state', 'index');
        $this->assign('book', $book);
        return $this->fetch();
    }


    /**
     * 消息推送
     */
    public function sendNotice()
    {
        $title = input('title/s', '', 'trim');
        $desc = input('desc/s', '', 'trim');
        //bookId和url只可以有一个
        $bookId = input('bookid/d', -1);//小说ID参数
        $url = input('url/s', '', 'trim');
        $channel = input('channel/a');
        $token_info = input('token_info/s');

        //if ($token_info == session('token')) {
            $noticeChannel = "";
            if (count($channel) > 1) {
                $alias = $channel;
                foreach ($channel as $val) {
                    $noticeChannel .= $val . ',';
                }
                $noticeChannel = rtrim($noticeChannel, ",");
            } else {
                $alias = $channel[0];
                $noticeChannel = $channel[0];
            }
            if (empty($channel)) {
                echo "<script>alert('未选择推送渠道');location.href='/admin/index/notice'</script>";
            }
            //  $alias = "tw10";
            $notice = new androidNotice();
            $res = $notice->sendAppNotice($title, $desc, $bookId, $url, $alias);
            $resArray = object_to_array($res);

            foreach ($resArray as $value) {
                if (is_array($value)) {
                    // $trace_id = $value['trace_id'];
                    $trace_id = $value['data']['id'];
                }
            }

            if ($res) {
                $noticeModel = new NoticeModel();
                if ($bookId == -1) {
                    $bookId = 0;
                }
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'book_id' => $bookId,
                    'url' => $url,
                    'create_time' => time(),
                    'notice_channel' => $noticeChannel,
                    'trace_id' => $trace_id,
                ];
                $noticeModel->saveData($data);
                echo "<script>alert('发布成功');location.href='/admin/index/notice'</script>";
            } else {
                echo "<script>alert('发布失败');location.href='/admin/index/notice'</script>";
            }

        //}

    }


    /**
     * 用户退出登录界面
     */
    public function logout()
    {
        //获取当前Session
        $session = Session::get();
        if ($this->is_login()) {
            if (!empty($session)) {
                $_SESSION = [];
            }
            session_unset();
            session_destroy();
            $this->redirect('index/login');
        } else {
            $this->redirect('index/login');
        }
    }

    /**
     * 生成验证码
     */
    public function verifyCom()
    {
        //生成验证码
        import("extend.org");
        $verify = new verify();
        $verify->entry(1);
    }

    /**
     * 生成极验验证码
     */
    public function verify()
    {
        $Check = new \org\geetest\web\StartCaptchaServlet;
        $response = $Check->geetest();
        echo $response;

    }

    /**
     * 二次验证
     */
    public function verifyAgain()
    {
        $Check = new \org\geetest\web\VerifyLoginServlet;
        $response = $Check->geetest();
        echo $response;

    }

    /**
     * 用户登录uid是否存在
     * return boolean
     */
    private function is_login()
    {
        if (empty($_SESSION)) {
            return false;
        } else {
            return true;
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
     * error函数
     */
    public function _error($info)
    {
        $data['status'] = 0;
        $data['info'] = $info;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }

    /**
     * 空函数
     */
    public function _empty()
    {
        return $this->fetch('index@public/404');
    }

    public function demo()
    {
        $Redis = new \org\Redis;

        $uid = $Redis::get('c_uid');
        if (empty($uid)) {
            $Redis::set('c_uid', 11, 60);
        } else {
            $uid = $Redis::get('c_uid');
            echo $uid;
        }
    }

    /**
     * 菜单状态调整
     */
    public function modifyMenuStatus()
    {
        $status = input('status/d', '0', 'intval');
        $id = input('id/d', '0', 'intval');
        Loader::model('Index')->modifyMenuStatus($id, $status);
    }


}
