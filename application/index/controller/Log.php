<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/28 0028
 * Time: 下午 2:52
 */

namespace app\index\controller;

use think\Controller;
use think\Request;

class Log extends Controller
{
    /**
     * @SWG\Post(
     *   path="/index/Log/appLog",
     *   tags={"日志"},
     *   summary="上传app错误日志",
     *   description="返回保存是否成功",
     *   consumes={"application/x-www-form-urlencoded"},
     *   @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         schema="raw",
     *         type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description="true|false"
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function appLog()
    {
        $data = file_get_contents('php://input');

        $date = date('Ymd', time());
        $dataJson = json_decode($data);
        $code = $dataJson->code;
        $folder = '.' . DS . 'log' . DS . $date;
        $dataStr = $data . "\n";
        if (!file_exists($folder)) {
            @mkdir($folder, 0755, true);
        }
        $file = $folder . DS . $code . '.log';
        return $this->writeFileForPv($file, $dataStr);
    }

    /**
     * 写日志
     * @param $file
     * @param $str
     * @return bool|resource
     */
    public function writeFileForPv($file, $str, $mode = 'a+')
    {
        @umask(0);
        $fp = fopen($file, $mode);
        if (!$fp) {
            return errorEncrypt(array());
        } else {
            @fwrite($fp, $str);
            @fclose($fp);
            return successEncrypt(array());
        }
    }
}