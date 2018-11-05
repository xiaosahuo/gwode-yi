<?php

function post($url, $postData)
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

function randomString()
{
    $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    str_shuffle($str);
    return substr(str_shuffle($str), 26, 10);
}


$random = randomString();
$key = 'sosx001jk)Kjfk2%$';
$time = time();
$mod = 'book'; // book , body

$sign = md5(md5("{$key}{$random}{$time}"));

// 书单列表

$content = post('https://api.tengwen.com/gbook.php', [
    'key' => $key,
    'time' => $time,
    'random' => $random,
    'mod' => $mod,
    'sign' => $sign,
]);

//print_r(json_decode($content, true));

//$content = [
//    0 => [
//        'id' => 199,
//        'name' => 'xxxx',
//        'state' => '完结',
//        'writer' => 'XX',
//        'catname' => '玄幻',
//        'time' => 15483245671,
//        'pic' => 'xxxxxxx'
//    ],
////    ...
//];

// 章节列表

$random = randomString();
$time = time();
$mod = 'chapter'; // book , body

$sign = md5(md5("{$key}{$random}{$time}"));

$content = post('https://api.tengwen.com/gbook.php', [
    'key' => $key,
    'time' => $time,
    'random' => $random,
    'mod' => $mod,
    'sign' => $sign,
    'book_id' => 258
]);

//var_dump(yield json_decode($content, true));
//print_r(json_decode($content, true));

//章节内容

$random = randomString();
$time = time();
$mod = 'body'; // book , body

$sign = md5(md5("{$key}{$random}{$time}"));

    $content = post('https://api.tengwen.com/gbook.php', [
        'key' => $key,
        'time' => $time,
        'random' => $random,
        'mod' => $mod,
        'sign' => $sign,
        'chapter_id' => 161840 //章节id
    ]);
print_r(json_decode($content, true));



