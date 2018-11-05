<?php

namespace app\command;

use think\console\Command;
use think\Db;
use app\admin\model\ChapterCopy as ChapterCopyModel;
use think\console\Input;
use think\console\Output;

/**
 * Created by PhpStorm.
 * User: wxt12
 * Date: 2018/10/22
 * Time: 17:41
 */
class Test extends Command
{

    const APIURL = 'https://api.tengwen.com/gbook.php';
    const KEY = 'sosx001jk)Kjfk2%$';
    public $url;
    public $key;
    public $time;
    public $random;
    public $sign;
    public $params;

    protected function configure()
    {
        $this->setName('Test')->setDescription("计划任务 Test");
    }

    protected function execute(Input $input, Output $output)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $output->writeln('Date Crontab job start...');
        echo 123;
//        $this->getChaterList();
//        $this->getContent();
 //       $this->modifyView();
       // $this->getNewBook();
        $output->writeln('Date Crontab job end...');
    }


    public function httpPost($url, $postData)
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

    public function randomString()
    {
        $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        return substr(str_shuffle($str), 26, 10);
    }

    public function getSign($key, $random, $time)
    {
        return md5(md5("{$key}{$random}{$time}"));
    }

    protected function configData()
    {
        $this->url = self::APIURL;
        $this->key = self::KEY;
        $this->time = time();
        $this->random = $this->randomString();
        $this->sign = $this->getSign($this->key, $this->random, $this->time);
        $this->params = [
            'key' => $this->key,
            'time' => $this->time,
            'random' => $this->random,
            'sign' => $this->sign
        ];
        return $data = [
            'url' => $this->url,
            'params' => $this->params,
        ];
    }

    public function getBookList()
    {
        $data = $this->configData();

        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try {
            $data['params']['mod'] = "book";
            $book = $this->httpPost($data['url'], $data['params']);
            print_r($book);
            $bookinfo = json_decode($book, true);
            print_r($bookinfo);
            if (!isset($bookinfo['data'])) {
                return file_put_contents('./collect.txt', '书籍获取失败' . "<br>", FILE_APPEND);
            }
            foreach ($bookinfo['data'] as $key => $value) {
                $params[$key]['cid'] = 3;
                $params[$key]['uid'] = 1;
                $params[$key]['model'] = 6;
                $params[$key]['title'] = $value['name'];
                $params[$key]['create_time'] = strtotime($value['time']);
                $params[$key]['update_time'] = strtotime($value['time']);
                $params[$key]['sort'] = 0;
                $params[$key]['status'] = 1;
                $params[$key]['view'] = 0;
                $params[$key]['trash'] = 1;
                $params[$key]['image'] = 0;
                $params[$key]['tstype'] = $this->getBookCate($value['catname']);
                $params[$key]['zuozhe'] = $value['writer'];
                $params[$key]['zishu'] = 207668;
                $params[$key]['zhishu'] = 90;
                $params[$key]['tj'] = 0;
                $params[$key]['desc'] = $value['descp'];
                $params[$key]['xstype'] = $this->getBookType($value['state']);
                $params[$key]['tips'] = 100;
                $params[$key]['gzzj'] = 0;
                $params[$key]['score'] = 34;
                $params[$key]['ishot'] = 0;
                $params[$key]['free_stime'] = 0;
                $params[$key]['free_etime'] = $value['id'];
                $params[$key]['isfree'] = 0;
                $params[$key]['public'] = $value['pic'];
                $params[$key]['consume'] = 0;
                $params[$key]['sex'] = $this->getBookCateSex($value['catname']);
                print_r($value['name']);
            }

            file_put_contents('./collect.txt', '书籍获取失败' . "<br>", FILE_APPEND);
            return Db::table('ien_book_copy')->insertAll($params);
        } catch (Exception $e) {
            file_put_contents('./collect.txt', '书籍获取失败' . "<br>", FILE_APPEND);
        }

    }


    private function getChaterList()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        //TODO  得到全部书籍，遍历得到数据，得到所有章节数，每次入库后，sleep(1);
        $bookList = Db::table('ien_book_copy')->field('id,free_etime')->select();

        foreach ($bookList as $k => $v) {

            try {

                $data = $this->configData();
                $data['params']['mod'] = 'chapter';
                $data['params']['book_id'] = $v['free_etime'];
                $data['params']['time'] = time();
                $chapter = $this->httpPost($data['url'], $data['params']);
                print_r($chapter);
                $chapterinfo = json_decode($chapter, true);
                unset($data);
                $newArray = $chapterinfo['data'];
                $params = [];

                if (count($newArray) <= 1000) {
                    for ($i = 0; $i < count($newArray); $i++) {
                        $params[$i]['cid'] = 5;
                        $params[$i]['uid'] = 1;
                        $params[$i]['model'] = 7;
                        $params[$i]['title'] = trim($newArray[$i]['name']);
                        $params[$i]['status'] = 1;
                        $params[$i]['create_time'] = 0;
                        $params[$i]['update_time'] = $newArray[$i]['id'];
                        $params[$i]['sort'] = 100;
                        $params[$i]['status'] = 1;
                        $params[$i]['view'] = 0;
                        $params[$i]['trash'] = 1;
                        $params[$i]['isvip'] = $newArray[$i]['is_vip'];
                        $params[$i]['bid'] = $v['id'];
                        $params[$i]['idx'] = $newArray[$i]['num'];

                    }
                    Db::table('ien_chapter_copy')->insertAll($params);
                    sleep(0.1);
                    $params = [];
                } else {
                    for ($i = 0; $i < intval(count($newArray) / 1000); $i++) {

                        for ($j = $i * 1000; $j < ($i * 1000) + 1000; $j++) {
                            $params[$j]['cid'] = 5;
                            $params[$j]['uid'] = 1;
                            $params[$j]['model'] = 7;
                            $params[$j]['title'] = trim($newArray[$j]['name']);
                            $params[$j]['status'] = 1;
                            $params[$j]['create_time'] = 0;
                            $params[$j]['update_time'] = $newArray[$j]['id'];
                            $params[$j]['sort'] = 100;
                            $params[$j]['status'] = 1;
                            $params[$j]['view'] = 0;
                            $params[$j]['trash'] = 1;
                            $params[$j]['isvip'] = $newArray[$j]['is_vip'];
                            $params[$j]['bid'] = $v['id'];
                            $params[$j]['idx'] = $newArray[$j]['num'];

                        }
                        print_r('+++++++');
                        print_r($i);
                        print_r('+++++++');
                        Db::table('ien_chapter_copy')->insertAll($params);
                        sleep(0.1);
                        $params = [];
                    }
                    print_r(count($newArray));
                    if (count($newArray) % 1000 === 0) {

                        return;
                    }
                    print_r('-------');

                    for ($i = (intval(count($newArray)/1000))*1000; $i < count($newArray); $i++) {
                        print_r($i);
                        $params[$i]['cid'] = 5;
                        $params[$i]['uid'] = 1;
                        $params[$i]['model'] = 7;
                        $params[$i]['title'] = trim($newArray[$i]['name']);
                        $params[$i]['status'] = 1;
                        $params[$i]['create_time'] = 0;
                        $params[$i]['update_time'] = $newArray[$i]['id'];
                        $params[$i]['sort'] = 100;
                        $params[$i]['status'] = 1;
                        $params[$i]['view'] = 0;
                        $params[$i]['trash'] = 1;
                        $params[$i]['isvip'] = $newArray[$i]['is_vip'];
                        $params[$i]['bid'] = $v['id'];
                        $params[$i]['idx'] = $newArray[$i]['num'];
                    }
                    // print_r($params);
                    Db::table('ien_chapter_copy')->insertAll($params);
                    sleep(0.1);
                    $params = [];
                    print_r(4444);
                }



            } catch (Exception $e) {
                // file_put_contents('./collect.txt', $v['free_etime']."<br>",FILE_APPEND);
            }
        }

    }

    public function getContent()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        //TODO  得到一部书籍章节内容，遍历书籍，每条请求一次，每次请求一条，入库,一条结束后sleep(0.5)
        $chapterList = Db::table('ien_chapter_copy')->field('id,update_time,bid')->order('id asc')->limit(1)->select();

        $num = 100;
        for ($i = 0; $i < intval(count($chapterList) / $num); $i++) {
            for ($j = $i * $num; $j < ($i * $num) + $num; $j++) {

                $res = $this->getContentInfo($chapterList[$j]['update_time']);
                print_r($chapterList[$j]['update_time']);
                $data['content'] = $res;
                Db::table('ien_chapter_copy')->where('id', $chapterList[$j]['id'])->update($data);
                $data = [];
              //  $chapterList[$j]['content'] = $res;   //批量数据
                sleep(0.05);
            }

        }

        if (count($chapterList) % $num === 0) {
            return;
        }

        for ($i = (intval(count($chapterList)/$num) * $num); $i < count($chapterList); $i++) {
            $res = $this->getContentInfo($chapterList[$i]['update_time']);
            print_r($chapterList[$i]['update_time']);
            $data['content'] = $res;
            Db::table('ien_chapter_copy')->where('id', $chapterList[$i]['id'])->update($data);
            $data = [];
            sleep(0.05);
        }


    }

    /*
    * 获取章节内容
    */
    public function getContentInfo($chapter_id)
    {
        $data = $this->configData();
        $data['params']['mod'] = 'body';
        $data['params']['chapter_id'] = $chapter_id;
        $data['params']['time'] = time();
        $body = $this->httpPost($data['url'], $data['params']);
        $bodyinfo = json_decode($body, true);
        print_r($bodyinfo);
        if (isset($bodyinfo['data'])) {
            print_r($bodyinfo['data']['body']);
            return $bodyinfo['data']['body'];
        }
    }


    public function getBookCate($catname)
    {
        switch ($catname) {
            case '其它':
                $tstype = 1;
                break;
            case '都市':
                $tstype = 2;
                break;
            case '灵异':
                $tstype = 3;
                break;
            case '现言':
                $tstype = 4;
                break;
            case '古言':
                $tstype = 5;
                break;
            case '玄幻':
                $tstype = 6;
                break;
            default:
                $tstype = 1;
                break;
        }
        return $tstype;
    }

    public function getBookCateSex($catname)
    {
        if ($catname == "都市" || $catname == "现言") {
            return 2;
        } else {
            return 1;
        }
    }

    public function getBookType($type)
    {
        if ($type == "完结") {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 批量更新函数
     * @param $data array 待更新的数据，二维数组格式
     * @param array $params array 值相同的条件，键值对应的一维数组
     * @param string $field string 值不同的条件，默认为id
     * @return bool|string
     */
    public function batchUpdate($table,$data, $field, $params = [])
    {
        if (!is_array($data) || !$field || !is_array($params)) {
            return false;
        }
        $updates = $this->parseUpdate($data, $field);
        $where = $this->parseParams($params);
        $fields = array_column($data, $field);
        $fields = implode(',', array_map(function ($value) {
            return "'" . $value . "'";
        }, $fields));
        return sprintf("UPDATE `%s` SET %s WHERE `%s` IN (%s) %s", $table, $updates, $field, $fields, $where);
    }

    /**
     * 将二维数组转换成CASE WHEN THEN的批量更新条件
     * @param $data array 二维数组
     * @param $field string 列名
     * @return string sql语句
     */
    public function parseUpdate($data, $field)
    {
        $sql = '';
        $keys = array_slice(array_keys(current($data)), 1);
        foreach ($keys as $column) {
            $sql .= sprintf("`%s` = CASE `%s` \n", $column, $field);
            foreach ($data as $line) {
                $sql .= sprintf("WHEN '%s' THEN '%s' \n", $line[$field], $line[$column]);
            }
            $sql .= "END,";
        }
        return rtrim($sql, ',');
    }

    /**
     * 解析where条件
     * @param $params
     * @return array|string
     */
    public function parseParams($params)
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = sprintf("`%s` = '%s'", $key, $value);
        }
        return $where ? ' AND ' . implode(' AND ', $where) : '';
    }

    public function modifyView()
    {
        $bookIDs = Db::table('ien_book')->field('id')->order('id asc')->select();
        $data = [];
        foreach($bookIDs as $key => $value)
        {
            $data[$key]['id'] = $value['id'];
            $data[$key]['view'] = rand(2536,15682);
        }
        $sql = $this->batchUpdate('ien_book', $data, 'id');
        Db::execute($sql);
    }




}