<?php
/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/9/3
 * Time: 9:18
 */

namespace app\admin\controller;

use think\Db;
use app\admin\model\Book as BookModel;
use app\admin\model\Chapter as ChapterModel;


class Book extends Base
{

    public function index()
    {

        $bookModel = new BookModel();
        $data = $bookModel->getBookAlllists();
        $page = $data->render();
        $this->assign('page', $page);
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function chapterLists()
    {
        $bid = input('id/d');
        $chapterModel = new ChapterModel();
        $data = $chapterModel->getChapterList($bid, 1);
        $page = $data->render();
        $this->assign('page', $page);
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function setVipStatus()
    {
        $id = input('post.id/d');
        $status = input('post.status/d');
        $data = ['isvip' => $status];
        $chapterModel = new ChapterModel();
        $res = $chapterModel->setChapterVipStatus($id, $data);
        if ($res !== false) {
            return json_encode(['code' => 0, 'data' => '', 'msg' => '调整成功']);
        } else {
            return json_encode(['code' => 1, 'data' => '', 'msg' => '调整失败']);
        }
    }

    public function sortUpdate()
    {
        $ids = input('post.id/a');
        $idxs = input('post.idx/a');
        $params = [];

        foreach ($ids as $key => $val) {
            $params[$key]['id'] = $val;
            $params[$key]['idx'] = $idxs[$key];
        }

        $chapterModel = new ChapterModel();
        $chapterModel->isUpdate(true)->saveAll($params);
        echo "<script>alert('更新成功');window.history.go(-1);</script>";
    }

    /**
     * 给小说添加格式  后期删除
     */
    public function addtest()
    {
        $bid = input('id/d');
        if ($bid != "") {
            $title = "。";
            $name = "。<br />  &nbsp;&nbsp;&nbsp;&nbsp; ";
            $title1 = "。<br />  &nbsp;&nbsp;&nbsp;&nbsp; ”";
            $name1 = "。”<br />  &nbsp;&nbsp;&nbsp;&nbsp;";
            $sql = "UPDATE ien_chapter SET content = replace(content,'{$title}','{$name}') WHERE bid = '{$bid}'";
            $res = Db::execute($sql);
            if ($res) {
                $sql1 = "UPDATE ien_chapter SET content = replace(content,'{$title1}','{$name1}') WHERE bid = '{$bid}'";
                $res1 = Db::execute($sql1);
                if ($res1) {
                    $this->success("字符替换完成！");
                } else {
                    $this->error("字符替换失败！");
                }
            } else {
                $this->error("字符替换出错请重试！");
            }
        }
    }

    /**
     * 多个换行替换  后期删除
     */
    public function replaceStr()
    {
        $bid = input('id/d');
        if ($bid != "") {
            $title = "<br /><br />";
            $name = "<br />";
            $sql = "UPDATE ien_chapter SET content = replace(content,'{$title}','{$name}') WHERE bid = '{$bid}'";
            $res = Db::execute($sql);
            if ($res) {
                $this->success("字符替换完成！");
            } else {
                $this->error("字符替换出错请重试！");
            }
        }
    }

    /**
     * 编辑排序 后期删除
     */
    public function editSort()
    {
        $bid = input('id/d');
        if ($bid != "") {
            $info = Db::table('ien_chapter_copy')->where('bid', $bid)->field('id,title,idx')->order('idx asc')->select();
            foreach ($info as $value) {
                $num = $this->findNum($value['title']);
                if ($num != '') {
                    $data = ['idx' => $num];
                } else {
                    $data = ['idx' => 999];
                }

                Db::table('ien_chapter_copy')->where('id', $value['id'])->update($data);
            }
        }
    }


    public function findNum($str = '')
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }
        $result = '';
        for ($i = 0; $i < strlen($str); $i++) {
            if (is_numeric($str[$i])) {
                $result .= $str[$i];
            }
        }
        return $result;
    }

    /**
     * 调整小说状态
     */
    public function changeStatus()
    {
        $id = input('post.id/d');
        $status = input('post.status/d');
        $data = ['status' => $status];
        $bookModel = new BookModel();
        $res = $bookModel->bookStatus($id, $data);
        if ($res !== false) {
            return json_encode(['code' => 0, 'data' => '', 'msg' => '调整成功']);
        } else {
            return json_encode(['code' => 1, 'data' => '', 'msg' => '调整失败']);
        }
    }


}