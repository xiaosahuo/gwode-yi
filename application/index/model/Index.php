<?php
namespace app\index\model;
use think\Model;
use think\Db;

class Index extends Model
{
    /**
     *  显示基本信息
     */
    public function add($date)
    {
        $date['ctime'] = time();
        $res = Db::name('users')->insert($date);
        return $res;
    }

    /**
     * 用户登录
     * param data
     */
    public function userList($username,$password)
    {
        $sql = 'SELECT uid,username,password,status FROM lz_users WHERE username=? AND password=?';
        $res = Db::query($sql,[$username,$password]);
        return $res;
    }

    /**
     * 用户名是否存在
     * param data
     */
    public function nameOne($name)
    {
        $sql = 'SELECT username FROM lz_users WHERE username=?';
        $res = Db::query($sql,[$name]);
        return $res;
    }

    /**
     *获取首页数据
     */
    public function homeData($data)
    {
        $res = Db::table('ien_book')
            ->field(['a.zuozhe'=>'bookAuthor','a.desc'=>'bookContenUrl','a.public'=>'bookIconUrl','bookid'=>'bookId','a.title' => 'bookName','a.view'=>'bookNum','a.tstype'=>'bookType','a.image','a.status'])
            ->alias('a')
            ->join('ien_home_page_copy b','a.id=b.bookid')
            ->where($data)
            ->where('a.status',1)
            ->limit(6)
            ->select();
        return $res;
    }

    /**
     * 首页banner图
     */
    public function banner($request)
    {
        $sql = 'SELECT * from ien_banner WHERE sex = ?';
        $res = Db::query($sql,[$request->sex]);
        return $res;
    }

    /*
     *首页换一换实现
     */
    public function changeModel($data,$page)
    {
        $res = Db::table('ien_book')
            ->field(['a.zuozhe'=>'bookAuthor','a.desc'=>'bookContenUrl','a.public'=>'bookIconUrl','bookid'=>'bookId','a.title' => 'bookName','a.view'=>'bookNum','a.tstype'=>'bookType','a.image','a.status'])
            ->alias('a')
            ->join('ien_home_page_copy b','a.id=b.bookid')
            ->where($data)
            ->where('a.status',1)
            ->page($page,6)
            ->select();
        return $res;
    }

    public function defaultData($data)
    {
        $res = Db::table('ien_book')
            ->field(['a.zuozhe'=>'bookAuthor','a.desc'=>'bookContenUrl','a.public'=>'bookIconUrl','a.id'=>'bookId','a.title' => 'bookName','a.view'=>'bookNum','a.tstype'=>'bookType','a.image','b.remark','b.sex','a.status'])
            ->alias('a')
            ->join('ien_home_page_copy b','a.id=b.bookid')
            ->where($data)
            ->where('a.status',1)
            ->select();
        return $res;
    }

    /*
     *书库数据
     */
    public function bookList($data,$page,$arr)
    {
        $res = Db::table('ien_book')
            ->field(['zuozhe'=>'bookAuthor','desc'=>'bookContenUrl','public'=>'bookIconUrl','id'=>'bookId','title' => 'bookName','view'=>'bookNum','tstype'=>'bookType','image','status'])
            ->order($data)
            ->page($page,10)
            ->where('id','not in',$arr)
            ->where('status',1)
            ->select();
        return $res;
    }

    /*
     *搜索
     */
    public function vague($res)
    {
        $data = Db::table('ien_book')
            ->field(['zuozhe'=>'bookAuthor','desc'=>'bookContenUrl','public'=>'bookIconUrl','id'=>'bookId','title' => 'bookName','view'=>'bookNum','tstype'=>'bookType','image','status'])
            ->where('title','like',"%$res%")
            ->where('status',1)
            ->select();
        return $data;
    }

    /*
     *男女书籍
     */
    public function series($data,$page,$arr,$sex)
    {

        $res = Db::table('ien_book')
            ->field(['zuozhe'=>'bookAuthor','desc'=>'bookContenUrl','public'=>'bookIconUrl','id'=>'bookId','title' => 'bookName','view'=>'bookNum','tstype'=>'bookType','image','status'])
            ->order($data)
            ->page($page,10)
            ->where('id','not in',$arr)
            ->where('status',1)
            ->where('sex',$sex)
            ->select();
        return $res;
    }

    /*
     *用户消费记录
     */
    public function userConsume($id,$page)
    {
        $res = Db::table('ien_app_read')
            ->field(['b.title'=>'bid','a.create_time'=>'time'])
            ->alias('a')
            ->join('ien_chapter b','a.zid=b.id')
            ->where('a.uid',$id)
            ->order('a.create_time ASC')
            ->page($page,10)
            ->select();
        foreach ($res as $k=>$v){
            $v['time'] = date("Y-m-d H:i:s",$v['time']);
            $v['zshu'] = 34;
            $res[$k] = $v;
        }
        return $res;
    }

    /*
     *单本消费记录
     */
    public function bookConsume($id)
    {
        $res = Db::table('ien_app_read')
            ->field("bid,max(create_time) as create_time,count(id) as shu")
            ->group('bid')
            ->order('create_time ASC')
            ->cache(3600)
            ->where('uid',$id)
            ->select();
        $n = [];
        foreach ($res as $k=>$v){
            $n[$k]['shu'] = $v['shu'];
            $n[$k]['bid'] = $v['bid'];
            $n[$k]['create'] = $v['create_time'];
            $n[$k]['zshu'] = Db::table('ien_chapter')->where('bid',$v['bid'])->where('isvip',1)->cache(3600)->count();
        }

        $x = [];
        foreach ($n as $k=>$v){
            if($v['shu'] == $v['zshu']){
                $x[$k]['bid'] = $v['bid'];
                $x[$k]['zshu'] = $v['zshu'];
                $x[$k]['time'] = $v['create'];
            }
        }
        return $x;
    }

    /*
     *小说书名获取
     */
    public function book($data)
    {
        $res = Db::table('ien_book')
            ->field(['title'])
            ->where('id',$data)
            ->find();
        return $res['title'];
    }

    /**
     * 获取CMS_FIELD的指定内容
     * @param $id
     * @return array|false|mixed|null|\PDOStatement|string|Model
     */
    public function getCmsField($id)
    {
        $info = DB::table('ien_cms_field')->field('options')->where('id',$id)->find();
        return $info;
    }

    public function getAdminAttach($var)
    {
        $info = Db::table('ien_admin_attachment')->where('id',$var)->find();
        return $info;
    }

}
