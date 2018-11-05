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
use app\admin\model\Banner as BannerModel;
use app\admin\model\Book as BookModel;

class Banner extends Base
{

    /*
     * banner列表
     */
    public function index()
    {
        $bannerModel = new BannerModel();
        $list = $bannerModel->bannerLists();
        $this->assign('data', $list);
        return $this->fetch();
    }

    /**
     * banner添加
     */
    public function add()
    {
        $bookModel = new BookModel();
        $booklist = $bookModel->booklists(0);
        $this->assign('booklist', $booklist);
        return $this->fetch();
    }

    /**
     * banner编辑
     */
    public function edit()
    {
        $id = input('id/d');
        $bookModel = new BookModel();
        $bannerModel = new BannerModel();
        $booklist = $bookModel->booklists(0);
        $info = $bannerModel->getBannerInfo($id);
        $this->assign('info', $info);
        $this->assign('booklist', $booklist);
        return $this->fetch();
    }

    /**
     * 保存banner信息
     */
    public function save()
    {
        $bookid = input('post.bookid/d');
        $id = input('post.id/d');
        $sex = input('post.sex/d');
        $file = request()->file('banner');

        if (empty($file)) {
            $this->error('请选择上传文件');
        }
        // 移动到框架应用根目录/public/uploads/banner/ 目录下
        $info = $file->validate(['size' => 1024 * 1024 * 2, 'ext' => 'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'banner');

        if ($info) {

            $imgUrl = '/uploads/banner/' . $info->getSaveName();
            $data = [
                'bookid' => $bookid,
                'public' => $imgUrl,
                'sex' => $sex,
            ];
            $bannerModel = new BannerModel();
            if (isset($id)) {
                //编辑信息
                $res = $bannerModel->editBannerInfo($id, $data);
                if ($res !== false) {
                    $this->success('编辑成功', 'Banner/index');
                } else {
                    $this->error('编辑失败');
                }
            } else {
                //添加信息
                $addID = $bannerModel->addBannerInfo($data);
                if ($addID) {
                    $this->success('添加成功', 'Banner/index');
                } else {
                    $this->error('添加失败');
                }
            }
        } else {
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }

    /**
     * banner删除
     */
    public function del()
    {
        $id = input('id/d');
        $bannerModel = new BannerModel();
        $res = $bannerModel->delBanner($id);
        if ($res !== false) {
            $this->success("删除成功", 'Banner/index');
        } else {
            $this->error("删除失败");
        }

    }


}