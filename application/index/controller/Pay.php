<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3
 * Time: 14:44
 */

namespace app\index\controller;


use think\Controller;

class Pay extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

}