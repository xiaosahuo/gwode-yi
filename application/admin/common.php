<?php

/**
 * 定向功能 for plan-list 
 */ 
function f_planlst($arr){
    $data = unserialize($arr);
    if(empty($data['city'])){
        return '';   
    }
    if($data['city']['isacl'] == '1'){
        return '已开启';
    }else{
        return '未开启';
    }
}

/**
 * 会员限制 for plan-list 
 */ 
function f_planLimit($param){
    if(isset($param)){
        return '有限制';
    }else{
        return '未限制';
    }
}

/**
 * 活动周期 for plan-list 
 */ 
function f_planExpiredate($arr){
    $data = unserialize($arr);
    if(empty($data['expire_date'])){
        return '';   
    }
    $arr = $data['expire_date'];
    $date = $arr['year'].'-'.$arr['month'].'-'.$arr['day'];
    $nowDate = date('Y-m-d',time());
    $var = $nowDate.'至'.$date;
    return $var;
}

/**
 * 投放设备 for plan-list 
 */ 
function f_plan_mobile($arr){
    $data = unserialize($arr);
    if(empty($data['mobile']['data'])){
        return '不限制';
    }else{
        $mdata = $data['mobile']['data'];
        $res = '不限制';
        if('1'==$data['mobile']['isacl']){
            $res = implode(',', $mdata);
        }
    }
    return $res;
}

/*
 * 处理小数
 */
function process_decimal($arr)
{

    $substr = stripos($arr,'.');
    if($substr == false){
        $str = $arr;
    }else{
        $substr = $substr+3;
        $str = substr($arr,0,$substr);
    }
    return $str;
}

/**
 * 取整
 */
function num_Round($arr)
{
    $integer = floor($arr);
    return $integer;
}


/**
 * 树形数组转化
 * @param $data
 * @param $pId  默认 0
 * @return array
 */
function getTree($data, $pId)
{
    $tree = [];
    foreach($data as $k => $v)
    {
        if($v['pid'] == $pId)
        {   //父亲找到儿子
            $v['pid'] = getTree($data, $v['id']) ? getTree($data, $v['id']) : 0;
            $tree[] = $v;
        }
    }
    return $tree;
}

/**
 * @param array $list 要转换的结果集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = 'listArea', $root = 0) {
    //创建Tree
    $tree = array();
    if (is_array($list)) {
        //创建基于主键的数组引用
        $refer = array();

        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }

        foreach ($list as $key => $data) {
            //判断是否存在parent
            $parantId = $data[$pid];

            if ($root == $parantId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parantId])) {
                    $parent = &$refer[$parantId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }

    return $tree;
}

/**
 * 获取随机打乱字符串
 * @return string
 */
function randomString()
{
    $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    return substr(str_shuffle($str), 26, 10);
}

function getSign($key, $random, $time)
{
    return md5(md5("{$key}{$random}{$time}"));
}

/**
 *
 * @param $str
 * @return mixed
 * php5.5以上 ：
 */
function mb_unserialize($str) {
    $out =  preg_replace_callback('#s:(\d+):"(.*?)";#s',function($match){return 's:'.strlen($match[2]).':"'.$match[2].'";';},$str);
    return unserialize($out);
}

/**
 * @param $password
 * @return string
 */
function encryptPassword($password)
{
    return md5($password);
}

function getIP()
{
    if(getenv('HTTP_CLIENT_IP')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR')) {
        $onlineip = getenv('REMOTE_ADDR');
    } else {
        $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
    }
    return $onlineip;
}

function httpPost($url, $postData)
{

    $curl = curl_init();
    $postData = http_build_query($postData);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}

function statsData($data)
{
    $res = [];
    $vip_order_pay_amount = 0;      //年费支付总数
    $vip_order_unpay_amount = 0;    //年费未支付总数
    $welth_order_pay_amount = 0;    //普通支付总数
    $welth_order_unpay_amount = 0;  //普通未支付总数
    $pay_total = 0;                 //支付总金额
    $unpay_total = 0;               //未支付总金额
    $welth_total = 0;               //普通支付总金额
    $vip_total = 0;                 //年费支付总金额
    for ($i = 0; $i < count($data); $i++) {
        if ($data[$i]['paytype'] == 2) {                //普通充值
            if ($data[$i]['status'] == 1) {             //已支付
                $welth_order_pay_amount += 1;           //普通支付个数
                $pay_total += $data[$i]['money'];       //支付总总额
                $welth_total += $data[$i]['money'];     //普通充值总金额
            } else {  //未支付
                $welth_order_unpay_amount += 1;         //普通未支付个数
                $unpay_total += $data[$i]['money'];     //未支付个数
            }
        } else if ($data[$i]['paytype'] == 1) {         //年费充值
            if ($data[$i]['status'] == 1) {             //已支付
                $vip_order_pay_amount += 1;             //年费支付个数
                $pay_total += $data[$i]['money'];       //年费支付总金额
                $vip_total += $data[$i]['money'];       //支付总金额
            } else {  //未支付
                $vip_order_unpay_amount += 1;
                $unpay_total += $data[$i]['money'];
            }
        }

    }

    $res = [
        'total_count' => $pay_total,
        'pay_count' => $pay_total,
        'unpay_count' => $unpay_total,
        'welth_total' => $welth_total,
        'vip_total' => $vip_total,
        'vip_order_pay_amount' => $vip_order_pay_amount,
        'vip_order_unpay_amount' => $vip_order_unpay_amount,
        'welth_order_pay_amount' => $welth_order_pay_amount,
        'welth_order_unpay_amount' => $welth_order_unpay_amount,
    ];
    return $res;
}

/**
 * 获取订单支付状态
 * @param $status
 * @return string
 */
function getOrderPayStatus($status)
{
    if($status == 1){
        return "已充值";
    }
    return "未充值";
}

/**
 * 获取订单支付方式
 * @param $status
 * @return string
 */
function getOrderPayMothod($status)
{
    if($status == 1){
        return "支付宝";
    }
    return "微信";
}

/**
 * 获取订单类型
 * @param $status
 * @return string
 */
function getOrderType($status)
{
    if($status == 1){
        return "VIP年费";
    }
    return "普通充值";
}

/**
 * 对象 转 数组
 * @param $obj
 * @return array|void
 */
function object_to_array($obj)
{
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function array_to_object($arr)
{
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }

    return (object)$arr;
}






