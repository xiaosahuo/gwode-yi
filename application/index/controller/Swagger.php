<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8 0008
 * Time: 下午 5:43
 */

namespace app\index\controller;

use think\Controller;

class Swagger extends Controller
{
    /**
     * @SWG\Swagger(
     *      schemes={"http","https"},
     *      produces={"application/json"},
     *      consumes={"application/json"},
     *      @SWG\Info(
     *          version="1.0.0",
     *          title="书城APP Api接口",
     *          description="api接口.<br/>swagger语法参考[swagger-php](https://github.com/zircote/swagger-php/blob/master/docs/Getting-started.md)和[swagger](http://swagger.io/specification/)",
     *          termsOfService="",
     *          @SWG\Contact(name="API Team"),
     *          @SWG\License(name="Private")
     *      ),
     *  )
     */
    public function index()
    {
        $path = '../application/index';
        $swagger = \Swagger\scan($path);
        header('Content-Type: application/json');
        $swagger_path = './swagger/docs/swagger.json';
        $res = file_put_contents($swagger_path, $swagger);
        if (true == $res) {
            $this->redirect('/../swagger/dist/index.html');
        } else {
            echo 'swagger.json写入失败！';
        }
    }
}

