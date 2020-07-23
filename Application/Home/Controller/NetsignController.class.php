<?php

namespace Home\Controller;

use Think\Controller;

class NetsignController extends BaseController
{
    protected $wsurl = "/api/login/appindex";//是否需要完善信息
    protected $ggurl = "/api/netsign/bullhorn";//公告列表
    protected $bzdsurl = "/api/netsign/swws";//公告列表
    protected $mysignurl = "/api/netsign/mysignname";//我的签名
    protected $delmysignurl = "/api/netsign/delmyself";//新增/修改签名
    protected $myselfurl = "/api/netsign/myselfinfo";//个人中心
    protected $sealurl = "/api/netsign/myseal";//我的印章
    protected $passwordurl = "/api/netsign/uppwd";//修改密码

    protected $web   = '';
    protected $cs    = '';

    public function __construct()
    {
        parent::__construct();
        $this->web = C('web');
        if(!isset($_SESSION["index_userinfo"]) || empty($_SESSION["index_userinfo"])){
            redirect('/home/index/index');
        }else{
            $user = $_SESSION["index_userinfo"];
            $this->cs = "/userid/".$user['userid']."/token/".$user['token'];
        }

    }
    //网签首页
    public function index(){
        //是否显示完善信息按钮
        $wsurl = $this->web.$this->wsurl.$this->cs;
        $data  =  curl_request($wsurl);
        $rebak = json_decode($data,true);
        if($rebak['code'] == 2){
            $this->_redirect();
        }
        $this->assign('isshow',$rebak['code']);
        $this->display();
    }
    //表证单书
    public function formlist(){

        $wsurl = $this->web.$this->bzdsurl.$this->cs;
        $data  =  curl_request($wsurl);
        $rebak = json_decode($data,true);
        if($rebak['code'] == 2){
            $this->_redirect();
        }
        $this->assign('toplist',$rebak['toplist']);
        $this->assign('nowpid',$rebak['toplist'][0]['id']);
        $this->display();
    }

    public function getformlist(){
        !empty($_REQUEST['page']) ? $page = '/page/'.$_REQUEST['page'] : $page = '/page/1';
        $pids = '/pid/'.$_REQUEST['pid'];
        !empty($_REQUEST['title']) ? $title = '/title/'.$_REQUEST['title'] : $title='';
        $wsurl = $this->web.$this->bzdsurl.$this->cs.$page.$pids.$title;
        $data  =  curl_request($wsurl);
        echo $data;
    }
    //税务文书详情
    public function forminfo(){
        $id = $_REQUEST['id'];
        $info = M('swws')->where("id='$id'")->find();
        $iframeurl = 'https://view.officeapps.live.com/op/embed.aspx?src='.C('web').$info['img'];
        $downurl = c('web').$info['img'];
        $this->assign('iframeurl',$iframeurl);
        $this->assign('downurl',$downurl);
        $this->display();
    }
    //完善信息页面
    public function wsinfo(){

        $wsurl = $this->web.$this->mysignurl.$this->cs;
        $data  =  curl_request($wsurl);
        $rebak = json_decode($data,true);
        if($rebak['code'] == 2){
            $this->_redirect();
        }
        $this->assign('info',$rebak['signname']);
        $this->display();
    }
    //添加签名
    public function mysignnameadd(){
        if(IS_POST){
            $user = $_SESSION["index_userinfo"];
            $bottom = dirname(dirname(dirname(dirname(__FILE__))));
            $path   = 'signname/'.$user['userid'].'/'.date('Ymd',time());
            if (!file_exists ( $path )) {
                $this->mkdirs($path);
            }

            $info = M('customer')->where("id='$user[userid]'")->find();
            if($info['signname']!=''){
                unlink($bottom."/".$info['signname']);
            }
            $imgurl = $this->base64_image_content($_POST['img'],$path);
            M('customer')->where("id='$user[userid]'")->save(array('signname'=>'/'.$imgurl));
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            echo json_encode($rebak);
        }else{
            $this->display();
        }

    }
    public function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        if (!$this->mkdirs(dirname($dir), $mode)) return FALSE;
        return @mkdir($dir, $mode);
    }
    /**
     * [将Base64图片转换为本地图片并保存]
     * @param  [Base64] $base64_image_content [要保存的Base64]
     * @param  [目录] $path [要保存的路径]
     */

    public function base64_image_content($base64_image_content,$path){
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $new_file = $path."/".date('Ymd',time())."/";
            if(!file_exists($new_file)){
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }

            $new_file = $new_file.time().".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                return '/'.$new_file;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    //删除签名
    public function delmysignname(){
        $wsurl = $this->web.$this->delmysignurl.$this->cs;
        $data  =  curl_request($wsurl);
        echo $data;
    }
    //个人中心
    public function myself(){
        $wsurl = $this->web.$this->myselfurl.$this->cs;
        $data  =  curl_request($wsurl);
        $rebak = json_decode($data,true);
        if($rebak['code'] == 2){
            $this->_redirect();
        }
        $this->assign('info',$rebak);
        $this->display();
    }
    //我的印章
    public function seal(){
        $wsurl = $this->web.$this->sealurl.$this->cs;
        $data  =  curl_request($wsurl);
        $rebak = json_decode($data,true);
        if($rebak['code'] == 2){
            $this->_redirect();
        }
        $this->assign('info',$rebak);
        $this->display();
    }
    //修改密码
    public function password(){
        if(IS_POST){
            $pj = "/oldpwd/".$_POST['oldpwd']."/newpwd/".$_POST['newpwd'];
            $wsurl = $this->web.$this->passwordurl.$this->cs.$pj;
            $data  =  curl_request($wsurl);
            echo $data;
        }else{
            $this->display();
        }
    }
    //退出登录
    public function loginout(){
        $_SESSION["index_userinfo"] = '';
        $rebak['code'] = 1;
        echo json_encode($rebak);
    }
    //公告通知列表
    public function notice(){
        $wsurl = $this->web.$this->ggurl.$this->cs;
        $data  =  curl_request($wsurl);
        $rebak = json_decode($data,true);
        if($rebak['code'] == 2){
            $this->_redirect();
        }
        $this->assign('list',$rebak['data']);
        $this->display();
    }
    //公告详情页面
    public function noticeinfo(){
        $id = $_REQUEST['id'];
        $info = M('bullhorn')->where("id='$id'")->find();
        $this->assign('info',$info);
        $this->display();
    }
}