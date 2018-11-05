<?php
/**
 * 定向功能 for plan-list 
 */ 
function f_planlst($arr){
    $data = unserialize($arr);
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
    $arr = $data['expire_date'];
    $date = $arr['year'].'-'.$arr['month'].'-'.$arr['day'];
    $nowDate = date('Y-m-d',time());
    $var = $nowDate.'至'.$date;
    return $var;
}

/**
 * 手机号正则验证
 */
function checkTel($tel){
    if(strlen($tel) == 11){
        //上面部分判断长度是不是11位
        if(preg_match("/^(1[3-9])\d{9}$/", $tel)){
            return 1;
        }
        return 0;
    } else {
        return 0;
    }
}

/**
 * http协议发送
 */
function curl_http($url,$xml)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    if(stripos($url,"https://")!==FALSE){
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }    else    {
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
    }
      curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    //传输文件
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    //运行curl
    $data = curl_exec($ch);
    curl_close($ch);
    //返回结果
    return $data;
}

/**
 *
 */
function getMoney($orderMoney)
{
    if ($orderMoney == '0.01') {
        $money = 3000;
    }elseif($orderMoney == '50') {
        $money = 7000;
    }elseif ($orderMoney == '100'){
        $money = 13000;
    }elseif ($orderMoney== '200'){
        $money = 26000;
    }elseif ($orderMoney == '500'){
        $money = 70000;
    }elseif ($orderMoney == '1000'){
        $money = 150000;
    }else{
        $money = $orderMoney;
    }

    return $money;
}

/**
 * 格式转化
 * @param $xml
 * @return mixed
 */
function FromXml($xml)
{
    if(!$xml){
        echo "xml数据异常！";
    }
    //将XML转为array
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $data;
}

//签名
function getSign($params)
{
    $newArr = [];
    ksort($params);        //将参数数组按照参数名ASCII码从小到大排序
    foreach ($params as $key => $item) {
        if (!empty($item)) {         //剔除参数值为空的参数
            $newArr[] = $key.'='.$item;     // 整合新的参数数组
        }
    }
    $stringA = implode("&", $newArr);         //使用 & 符号连接参数
    $stringSignTemp = $stringA."&key="."deyichengdongjiwendeyichengdonga";        //拼接key
    // key是在商户平台API安全里自己设置的
    $stringSignTemp = MD5($stringSignTemp);       //将字符串进行MD5加密
    $sign = strtoupper($stringSignTemp);      //将所有字符转换为大写
    return $sign;
}

/*
 *微信app支付
 */
function ToXml($data=array())
{
    if(!is_array($data) || count($data) <= 0)
    {
        return '数组异常';
    }
    $xml = "<xml>";
    foreach ($data as $key=>$val)
    {
        if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
        }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
    }
    $xml.="</xml>";
    return $xml;
}
//随机码32位
function rand_code()
{
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
    $str = str_shuffle($str);
    $str = substr($str,0,32);
    return  $str;
}

//接口加密 函数
function dataEncrypt($data)
{
    $iv = "12gf-tgKhi-turfA";//16位
    $privateKey = 'dgitlhoyt345jgi9';//16位
    header('Content-Type:application/json; charset=utf-8');
    $data_front = json_encode($data,JSON_UNESCAPED_UNICODE);
    $data_front_c = base64_encode(openssl_encrypt($data_front,"AES-128-CBC",$privateKey,OPENSSL_RAW_DATA,$iv));
    return $data_front_c;
}

//接口解密
function dataDecrypt($data = '')
{
    $iv = "12gf-tgKhi-turfA";//16位
    $privateKey = 'dgitlhoyt345jgi9';//16位
    header("Content-type:application/json;charset=utf-8");
    if(!isset($data) || $data == ''){
        $data = file_get_contents('php://input');
    }
    $re_c = openssl_decrypt(base64_decode($data),"AES-128-CBC",$privateKey,OPENSSL_RAW_DATA,$iv);
    $data_json = json_decode($re_c);
    return $data_json;
}

/**
 * 调试方法
 * @return string
 */
function jsonData()
{
    header("Content-type:application/json;charset=utf-8");
    $data = file_get_contents('php://input');
    return json_decode($data); //返回null是  注意json格式  属性和值必须“双引号”
}

//错误信息加密
function errorEncrypt($msg = '参数错误', $code = 404)
{
    $output = array('code'=>$code,'data'=>'','msg'=>$msg);
    return dataEncrypt($output);
   // return json_encode($output);
}

function successEncrypt($data = [])
{
    $output = array('code'=>200,'data'=>$data,'msg'=>'');
    return dataEncrypt($output);
   // return json_encode($output);
}

function isHttp($url)
{
    if (preg_match('/^http(s)?:\\/\\/.+/', $url)) {
        return true;
    } else {
        return false;
    }
}




