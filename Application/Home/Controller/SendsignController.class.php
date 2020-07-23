<?php

namespace Home\Controller;

use Think\Controller;

class SendsignController extends BaseController
{
    protected $ksqsurl = "/api/netsign/index";//开始签署页面
    protected $mburl = "/api/netsign/templates";//模板列表
    protected $mbxqurl = "/api/netsign/templateinfo";//模板详情
    protected $signnameurl = "/api/netsign/signname";//添加签署人->签名
    protected $sealnameurl = "/api/netsign/seallist";//添加签署人->印章
    protected $alllisturl = "/api/netsign/signall";//签署文件页面->全部
    protected $mysignlisturl = "/api/netsign/mysignlist";//签署文件页面->我发起
    protected $waitmysignlisturl = "/api/netsign/waitmysign";//签署文件页面->我签署
    protected $userlisturl = "/api/netsign/zzjgtlist";//机构设置
    protected $userinfourl = "/api/netsign/userinfo";//人员详情
    protected $forminfourl = "/api/netsign/signinfo";//模板详情页面.
    protected $imginfourl = "/api/netsign/signlook";//文件预览
    protected $chosesealurl = "/api/netsign/showseal";//文件预览
    protected $signnamesignurl = "/api/netsign/signshow";//签署详情页面->点击签署按钮->进入操作图片页面
    protected $zzjgurl  = "/api/send/zzjg";//自然人获取组织架构
    protected $zrralllisturl = "/api/send/index";//自然人受理页面
    protected $churl = "/api/send/ch";//自然人撤回操作
    protected $zgylisturl = "/api/netsign/nosignlist";//自然人申请专管员管理页面
    protected $zgy_sendsignurl = "/api/netsign/zgy_sendsign";//专管员发起签署页面
    protected $jqurl = "/api/netsign/nosign";//操作签署文件页面->拒签

    protected $web = '';
    protected $cs = '';

    private $AppKey = '';
    private $AppSecret = '';
    private $AgentId = '';

    public function __construct()
    {
        // $_SESSION["index_userinfo"]['userid']=925;
        // $_SESSION["index_userinfo"]['token']='2457fd2609963606b78ec6663bad2e4a';
        parent::__construct();
        $this->web = C('web');
        $this->AppKey = C('AppKey');
        $this->AppSecret = C('AppSecret');
        $this->AgentId = C('AgentId');
        if (!isset($_SESSION["index_userinfo"]) || empty($_SESSION["index_userinfo"])) {
            redirect('/home/index/index');
        } else {
            $user = $_SESSION["index_userinfo"];
            $this->cs = "/userid/" . $user['userid'] . "/token/" . $user['token'];
        }
    }
    //首页
    public function index(){

        $wsurl = $this->web.$this->ksqsurl.$this->cs;
        $data  =  httpGetRequest($wsurl);

        $rebak = json_decode($data,true);
        if($rebak['code'] == 2){
            $this->_redirect();
        }
        $this->assign('mycount',$rebak['mycount']);//待我签署数量
        $this->assign('othercount',$rebak['othercount']);//待他人签署数量
        $this->assign('list',$rebak['data']);//待他人签署数量
        $this->assign('sendcount',$rebak['sendcount']);//自然人未处理数量
        //dump($_SESSION['index_userinfo']);
       // echo $_SESSION['index_userinfo']['usertype'];
        if($_SESSION['index_userinfo']['usertype'] == '3'){
            $this->display();
        }else if($_SESSION['index_userinfo']['usertype'] == '2'){
            $this->display('zgynewindex');
        }else{
            $this->display('newindex');
        }

    }
    //拍照
    public function photo(){
        if($_SESSION['index_userinfo']['usertype'] == 2 || $_SESSION['index_userinfo']['usertype'] == '3'){
            //echo '232323';
            $this->display('phpoto');
        }else{
            //echo '23123';
            $this->display('newphpoto');
        }

    }

    public function test(){
        $text = file_get_contents('blob:http://wq.lagewa.com/646e9924-9d3d-493e-91bd-1ba214e8ee9e');
        dump($text);
        file_put_contents('/alidata/www/newdisk/wqxts/linshi/2.png',$text,FILE_APPEND);
    }

    //拍照->删除临时图片
    public function dellinshi(){
        $id = $_POST['id'];
        $info = M('linshi')->where("id='$id'")->find();
        $bottom = dirname(dirname(dirname(dirname(__FILE__))));
        unlink($bottom."/".$info['img']);
        M('linshi')->where("id='$id'")->delete();
        echo json_encode(array('code'=>1));
    }

    //上传图片
    public function upload(){
        $user = $_SESSION["index_userinfo"];
        if(empty($user)){
            $rbak['code'] = 2;
            echo json_encode($rbak);
            die;
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','doc','docx');// 设置附件上传类型
        $upload->rootPath  =     './images/'; // 设置附件上传根目录
        $upload->savePath  =     $user['userid'].'/'; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $bak['msg']  = $upload->getError();
            $bak['code'] = 0;
        }else{// 上传成功

            $bak['data'] =  '/images/'.$info['file']['savepath'].$info['file']['savename'];
            M('linshi')->add(array('img'=> $bak['data']));
            $id =  M('linshi')->getLastInsID();
            $bak['id'] = $id;
            $bak['msg']  = '上传成功';
            $bak['code'] = 1;

        }
        echo json_encode($bak);
    }
    //发起签署页面
    public function signdo(){
        $idstr = $_REQUEST['id'];
        $idarr = explode(',',$idstr);
        $imglist = M('linshi')->where("id in ($idstr)")->select();
        $this->assign('imglist',$imglist);
        $id = $idarr[0];
        if(isset($_REQUEST['mid'])){
            $minfo = M('template')->where("id='$_REQUEST[mid]'")->find();
            $wsurl = $this->web.$this->mbxqurl.$this->cs.'/id/'.$_REQUEST['mid'];
            $data  =  curl_request($wsurl);
            $rebak = json_decode($data,true);
            if($rebak['code']==2){
                $this->_redirect();
            }
            $this->assign('list',$rebak['data']);
            $this->assign('minfo',$minfo);
        }
        $info = M('linshi')->where("id='$id'")->find();
        $this->assign('info',$info);
        $this->assign('time',time());
        $this->assign('count',count($idarr));
        $this->assign('id',$idstr);
        $this->assign('worker',$_SESSION["index_userinfo"]['woker']);

        //选择签名列表
        $signnameurl = $this->web.$this->signnameurl.$this->cs;
        $signdata  =  curl_request($signnameurl);
        $signbak   = json_decode($signdata,true);
        $this->assign('first',$signbak['first']);
        $this->assign('signnamelist',$signbak['data']);
        $this->assign('people',$signbak['people']);
        //选择盖章
        $sealnameurl = $this->web.$this->sealnameurl.$this->cs;
        $sealdata =   curl_request($sealnameurl);
        $sealrebak = json_decode($sealdata,true);
        $this->assign('seallist',$sealrebak['data']);
        //dump($signbak);
        if($_SESSION['index_userinfo']['usertype'] == 2 || $_SESSION['index_userinfo']['usertype'] == '3'){
            $this->display();
        }else{
            $wsurl = $this->web.$this->zzjgurl.$this->cs.'/title/'.$_REQUEST['title'];
           // echo $wsurl;
            $data  =  curl_request($wsurl);
            $rebak =  json_decode($data,true);
            if($rebak['code']==2){
                $this->_redirect();
            }

            $this->assign('list',$rebak['data']);
            $this->display('newsigndo');
        }

    }
    //自然人获取组织架构
    public function getzzjg(){
        $wsurl = $this->web.$this->zzjgurl.$this->cs.'/title/'.$_REQUEST['title'];
        
        $data  =  curl_request($wsurl);
        echo $data;
    }

    //动态无限级获取签名数据
    public function getsignaturelist(){
        $fid = $_REQUEST['fid'];
        $title = $_REQUEST['title'];
        $signnameurl = $this->web.$this->signnameurl.$this->cs."/fid/".$fid."/title/".$title;
        $signdata  =  curl_request($signnameurl);
        echo $signdata;
    }
    //动态无限级获取印章
    public function getseallist(){
        $id = $_POST['id'];
        $sealnameurl = $this->web.$this->sealnameurl.$this->cs."/id/".$id;
        $sealdata =   curl_request($sealnameurl);
        echo $sealdata;
    }
    //模板选择列表
    public function templist(){
        $imgid = $_REQUEST['imgid'];
        $this->assign('imgid',$imgid);
        $this->assign('htid',$_REQUEST['htid']);
        $this->display();
    }
    //获取模板数据
    public function gettemplist(){
        !empty($_REQUEST['page']) ? $page = '/page/'.$_REQUEST['page'] : $page = '/page/1';
        !empty($_REQUEST['title']) ? $title = '/name/'.$_REQUEST['title'] : $title='';
        $wsurl = $this->web.$this->mburl.$this->cs.$page.$title;
        $data  =  curl_request($wsurl);
        echo $data;
    }
    //模板详情页面
    public function tempinfo(){
        $id = $_REQUEST['id'];
        $imgid = $_REQUEST['imgid'];
        $wsurl = $this->web.$this->mbxqurl.$this->cs.'/id/'.$id;
        $this->assign('htid',$_REQUEST['htid']);
        $data  =  curl_request($wsurl);
        $rebak = json_decode($data,true);
        if($rebak['code']==2){
            $this->_redirect();
        }

        $this->assign('name',$rebak['name']);
        $this->assign('list',$rebak['data']);
        $this->assign('imgid',$imgid);
        $this->assign('mid',$id);
        $this->display();
    }
    //印章列表
    public function seallist(){
        $this->display();
    }

    //选择签名列表
    public function signaturelist(){
        $this->display();
    }
    //全部签署文件
    public function alllist(){
        $user = $_SESSION["index_userinfo"];
        if($_SESSION['index_userinfo']['usertype'] == '2' || $_SESSION['index_userinfo']['usertype'] == '3'){
            $this->display();
        }else{
            $this->display('newalllist');
        }

    }
    //获取全部签署文件
    public function getalllist(){
        $page = $_REQUEST['page'];
        $wsurl = $this->web.$this->alllisturl.$this->cs."/page/".$page;
        //echo $wsurl;
        $data  =  curl_request($wsurl);
        echo $data;
    }
    //获取已受理文件
    public function getzrralllist(){
        $page = $_REQUEST['page'];
        $type = $_REQUEST['type'];
        $wsurl = $this->web.$this->zrralllisturl.$this->cs."/page/".$page."/type/".$type;
        //echo $wsurl;
        $data  =  curl_request($wsurl);
        echo $data;
    }

    //全部签署->我发起
    public function mysignlist(){
        // echo "<pre>";print_r($_SESSION['index_userinfo']['usertype'] == '2');exit;
        if($_SESSION['index_userinfo']['usertype'] == '2' || $_SESSION['index_userinfo']['usertype'] == '3'){
            $this->display();
            // echo "123";exit;
        }else{
            $this->display('newmysignlist');
        }
    }
    //获取全部签署文件->我发起
    public function getmysignlist(){
        $page = $_REQUEST['page'];
        $wsurl = $this->web.$this->mysignlisturl.$this->cs."/page/".$page;
        //echo $wsurl;
        $data  =  curl_request($wsurl);
        echo $data;
    }
    //全部签署->我签署
    public function waitmysignlist(){
        $this->display();
    }
    //获取全部签署文件->我签署
    public function getwaitmysignlist(){
        $page = $_REQUEST['page'];
        $wsurl = $this->web.$this->waitmysignlisturl.$this->cs."/page/".$page;
        //echo $wsurl;
        $data  =  curl_request($wsurl);
        echo $data;
    }

    //组织架构
    public function userlist(){
        //选择签名列表
        $signnameurl = $this->web.$this->signnameurl.$this->cs;
        //echo $signnameurl;
        $signdata  =  curl_request($signnameurl);
        $signbak   = json_decode($signdata,true);
        if($signbak['code']==2){
            $this->_redirect();
        }
        $this->assign('first',$signbak['first']);
        $this->assign('signnamelist',$signbak['data']);
        $this->assign('people',$signbak['people']);

        if($_SESSION['index_userinfo']['usertype'] == '2' || $_SESSION['index_userinfo']['usertype'] == '3'){
            $this->display();
        }else{
            $wsurl = $this->web.$this->zzjgurl.$this->cs.'/title/'.$_REQUEST['title'];
            // echo $wsurl;
            $data  =  curl_request($wsurl);
            $rebak =  json_decode($data,true);
            if($rebak['code']==2){
                $this->_redirect();
            }

            $this->assign('list',$rebak['data']);
            $this->display('newuserlist');
        }
    }
    //获取组织架构下级内容
    public function getuserlist(){
        $fid = $_REQUEST['fid'];
        $title = $_REQUEST['title'];
        $signnameurl = $this->web.$this->signnameurl.$this->cs."/fid/".$fid."/title/".$title;
        $signdata  =  curl_request($signnameurl);
        echo $signdata;
    }

    //用户详情信息
    public function userinfo(){
        $id = $_REQUEST['id'];
        $signnameurl = $this->web.$this->userinfourl.$this->cs.'/id/'.$id;
        $info  =  json_decode(curl_request($signnameurl),true);
        if($info['code']==2){
            $this->_redirect();
        }

        $this->assign('info',$info['data']);
        $this->display();
    }
    //模板详情页面
    public function forminfo(){
        $id = $_REQUEST['id'];
        $signnameurl = $this->web.$this->forminfourl.$this->cs.'/id/'.$id;
        $info  =  json_decode(curl_request($signnameurl),true);
        if($info['code']==2){
            $this->_redirect();
        }

        $this->assign('id',$id);
        $user = $_SESSION["index_userinfo"];
        $this->assign('uid',$user['userid']);
        $this->assign('username',$info['username']);
        $this->assign('start_time',$info['start_time']);
        $this->assign('end_time',$info['end_time']);
        $this->assign('topimage',$info['topimage']);
        $this->assign('status',$info['status']);
        $this->assign('name',$info['name']);
        $this->assign('list',$info['data']);
        $this->assign('id',$id);
        $this->display();
    }
    //文件预览
    public function imginfo(){
        $id = $_REQUEST['id'];
        $signnameurl = $this->web.$this->imginfourl.$this->cs.'/id/'.$id;
        $info  =  json_decode(curl_request($signnameurl),true);
        if($info['code']==2){
            $this->_redirect();
        }
        $this->assign('list',$info['data']);
        $this->assign('id',$id);
        $this->display();
    }

    //选择印章列表
    public function getchoseseallist(){
        $title = $_POST['title'];
        $signnameurl = $this->web.$this->chosesealurl.$this->cs.'/title/'.$title;
        echo curl_request($signnameurl);

    }
    //确定签署按钮
    public function signdoing(){
        
        $ismake = $_POST['ismake'];//是否自动流转，1：是，2：否
        $mid    = $_POST['mid'];//模板id
        $endtime = $_POST['endtime'];//结束时间
        $tempatename = $_POST['tempatename'];//模板名称
        $templateson = $_POST['templateson'];//模板操作流程
        $templateson = $_POST['templateson'];
        $htid        = $_POST['htid'];
        $user = $_SESSION["index_userinfo"];
        $userid = $user['userid'];
        if(empty($htid)){
            $table='linshi';
        }else{
            $table= 'sendimg';
        }
            if ($ismake == 1) {
                if (empty($mid)) {
                    $rebak['code'] = 9;
                    $rebak['msg'] = '请选择模板';
                    echo json_encode($rebak);
                    die;
                }
                $data['uid'] = $userid;
                $data['mid'] = $mid;
                $data['endtime'] = strtotime($endtime);
                $data['starttime'] = time();
                $data['savetime'] = time();
                $total = M('templateson')->where("fid='$mid'")->select();
                $data['totalnode'] = count($total);
                $data['nownode'] = 1;
                $data['status'] = 1;
                M('sign')->add($data);
                $nowsignid = M('sign')->getLastInsID();

                foreach ($total as $key => $value) {
                    $data1['fid'] = $nowsignid;
                    $data1['issign'] = 1;
                    $data1['tsid'] = $value['id'];
                    $data1['remark'] = '';
                    M('signson')->add($data1);
                }
                if (empty($_POST['img'])) {
                    $rebak['code'] = 8;
                    $rebak['msg'] = '签署文件不能为空';
                    echo json_encode($rebak);
                    die;
                }
                $chuliimg = trim($_POST['img'],",");
                $imglist = M($table)->where("id in($chuliimg)")->select();
                foreach($imglist as $key=>$value){
                    $add['sid'] = $nowsignid;
                    $add['img'] = $value['img'];
                    M('signimg')->add($add);
                    $nowinsertid = M('signimg')->getLastInsID();
                    $sidarr[] = $nowinsertid;
                }
                $sidimg = implode(',', $sidarr);
                M("sign")->where("id='$nowsignid'")->save(array('img' => $sidimg));
                M($table)->where("id in($chuliimg)")->delete();
                $rebak['code'] = 1;
                $rebak['msg'] = '成功';
                $rebak['sid'] = $nowsignid;
            } else {
                if (empty($tempatename)) {
                    $rebak['code'] = 3;
                    $rebak['msg'] = '模板名称不能为空';
                    echo json_encode($rebak);
                    die;
                }
                if (empty($templateson)) {
                    $rebak['code'] = 4;
                    $rebak['msg'] = '操作流程不能为空';
                    echo json_encode($rebak);
                    die;
                }
                $data2['name'] = $tempatename;
                $data2['creattime'] = time();
                $data2['status'] = 1;
                $data2['totalnode'] = count($templateson);
                $res = M('template')->add($data2);
                $nowtemplateid = M('template')->getLastInsID();

                foreach ($templateson as $key => $value) {
                    $data3['fid'] = $nowtemplateid;
                    if (empty($value['uid'])) {
                        $rebak['code'] = 5;
                        $rebak['msg'] = '操作流程中含有uid为空的情况，这样不正确';
                        $rebak['data'] = $templateson;
                        echo json_encode($rebak);
                        die;
                    }
                    $data3['uid'] = $value['uid'];
                    if (empty($value['orders'])) {
                        $rebak['code'] = 6;
                        $rebak['msg'] = '操作流程中含有orders为空的情况，这样不正确';
                        echo json_encode($rebak);
                        die;
                    }
                    $data3['orders'] = $value['orders'];
                    if (empty($value['type'])) {
                        $rebak['code'] = 7;
                        $rebak['msg'] = '操作流程中含有type为空的情况，这样不正确';
                        echo json_encode($rebak);
                        die;
                    }
                    $data3['type'] = $value['type'];
                    M('templateson')->add($data3);
                }

                $data['uid'] = $userid;
                $data['mid'] = $nowtemplateid;
                $data['endtime'] = strtotime($endtime);
                $data['starttime'] = time();
                $data['savetime'] = time();
                $total = M('templateson')->where("fid='$nowtemplateid'")->select();
                $data['totalnode'] = count($templateson);
                $data['nownode'] = 1;
                $data['status'] = 1;
                $data['htid'] = $htid;
                M('sign')->add($data);
                $nowsignid = M('sign')->getLastInsID();

                foreach ($total as $key => $value) {
                    $data1['fid'] = $nowsignid;
                    $data1['issign'] = 1;
                    $data1['tsid'] = $value['id'];
                    $data1['remark'] = '';
                    M('signson')->add($data1);
                }
                if (empty($_POST['img'])) {
                    $rebak['code'] = 8;
                    $rebak['msg'] = '签署文件不能为空';
                    echo json_encode($rebak);
                    die;
                }
                $chuliimg = trim($_POST['img'],",");
                $imglist = M($table)->where("id in($chuliimg)")->select();
                foreach($imglist as $key=>$value){
                    $add['sid'] = $nowsignid;
                    $add['img'] = $value['img'];
                    M('signimg')->add($add);
                    $nowinsertid = M('signimg')->getLastInsID();
                    $sidarr[] = $nowinsertid;
                }
                $sidimg = implode(',', $sidarr);
                M("sign")->where("id='$nowsignid'")->save(array('img' => $sidimg));
                M($table)->where("id in($chuliimg)")->delete();
                $rebak['code'] = 1;
                $rebak['msg'] = '成功';
                $rebak['sid'] = $nowsignid;
            }

            if(!empty($htid)){
                M('senduid')->where("id='$htid'")->save(array('status'=>1));
            }

        $signinfo =  M("sign")->where("id='$nowsignid'")->find();
        $templateinfo = M("template")->where("id='$signinfo[mid]'")->find();
        $userlist = M("templateson")->where("fid='$signinfo[mid]'")->order("orders asc")->limit(1)->select();
        $userids = $userlist[0]['uid'];

        $userinfo_di = M("customer")->where("id='$userids'")->find();
        $sj = date("Y-m-d H:i:s");
        $this->sendMes($userinfo_di['dduserid'],'{"msgtype":"text","text":{"content":"您一个任务名为【'.$templateinfo['name'].'】任务文件待签署\n发起时间为\n'.$sj.'"}}');

        echo json_encode($rebak);
    }
    //确定签署页面
    public function signnamesign(){
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        $orders = $_REQUEST['orders'];
        $signnameurl = $this->web.$this->signnamesignurl.$this->cs."/id/".$id."/type/".$type."/orders/".$orders;
        $signdata  =  curl_request($signnameurl);
        $signbak   = json_decode($signdata,true);
        if($signbak['code']==2){
            $this->_redirect();
        }
        $this->assign('signbtn',$signbak['signbtn']);
        $this->assign('sid',$signbak['sid']);
        $this->assign('imglist',$signbak['data']);
        $this->assign('timeimg',$this->creatimg());
        $this->assign('id',$id);
        $this->assign('orders',$orders);
        $this->display();
    }

    //创建时间图片
    public function creatimg(){
        $str = M('imgtime')->where("id=1")->find();
        $nowtime = date('Y-m-d',time());
        if($nowtime != $str['str']){
            $file = imagecreate(100,50); //先生成图片资源
            $color =imagecolorallocate($file,255,255,255);   //白色
            imagecolortransparent($file,$color);
            $black = imagecolorallocate($file, 0, 0, 0);
            imagefill($file,0,0,$color);   //两个数字是颜色填充从哪里开始，0，0代表左上角
            imagestring($file, 4,10,20, $nowtime , $black);
            $bottom = dirname(dirname(dirname(dirname(__FILE__))));
            $path = $bottom.'/Public/timeimg/time.png';
            M('imgtime')->where("id=1")->save(array('str'=>$nowtime));
            imagepng($file, $path);
        }
        $img = "/Public/timeimg/time.png";
        return $img;
    }

    //数据流上传图片
    public function base64imgsave($img,$userid,$signid){

        //文件夹日期
        $ymd = date("Ymd");

        //图片路径地址
        //$basedir = 'upload/base64/'.$ymd.'';
        $basedir   = 'images/'.$userid.'/'.date('Ymd',time());

        $fullpath = $basedir;
        if(!is_dir($fullpath)){
            mkdir($fullpath,0777,true);
        }
        $types = empty($types)? array('jpg', 'gif', 'png', 'jpeg'):$types;

        $img = str_replace(array('_','-'), array('/','+'), $img);

        $b64img = substr($img, 0,100);

        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $b64img, $matches)){

            $type = $matches[2];
            if(!in_array($type, $types)){
                return array('status'=>1,'info'=>'图片格式不正确，只支持 jpg、gif、png、jpeg哦！','url'=>'');
            }
            $img = str_replace($matches[1], '', $img);
            $img = base64_decode($img);
            $photo = '/'.md5(date('YmdHis').rand(1000, 9999)).'.'.'png';
            file_put_contents($fullpath.$photo, $img);

            $ary['status'] = 1;
            $ary['info'] = '保存图片成功';
            $ary['url'] = $basedir.$photo;

            //处理图片
            $insert['img'] = '/'.$basedir.$photo;
            $insert['sid'] = $signid;
            M('signimg')->add($insert);
            $nowinsertid = M('signimg')->getLastInsID();
            //$sidarr[] = $nowinsertid;
           // $sidstr = implode(',',$sidarr);
           // M('sign')->where("id='$signid'")->save(array('img'=>$sidstr));
            return $nowinsertid;

        }

        $ary['status'] = 0;
        $ary['info'] = '请选择要上传的图片';

        return $ary;

    }
    //确定签署按钮操作
    public function sendsigndo(){


        $id = $_POST['id'];
        $orders = $_POST['orders'];
        $userid = $_SESSION['index_userinfo'];
        $signinfos = M('sign')->where("id='$id'")->find();
        $templateson = M('templateson')->where("fid='$signinfos[mid]' and uid='$userid[userid]' and orders='$orders'")->find();
        $issign = M("signson")->where("fid='$id' and tsid='$templateson[id]'")->find();

        if($issign['issign'] > 1){
            $rebak['code'] = 0;

            $rebak['msg'] = '该文件您已经签署过，请勿重复操作';
            //$rebak['msg'] =  $id."\n".$templateson[id];
            echo json_encode($rebak);
            die;
        }

        $img = $_POST['arr'];
        $pw = $_POST['pw'];
        $ph = $_POST['ph'];
        $sidstr = $this->img_save($id,$img,$pw,$ph,$orders);

        M('sign')->where("id='$id'")->save(array('img'=>$sidstr));
        $signinfo =  M('sign')->where("id='$id'")->find();
        $temson = M('templateson')->where("fid='$signinfo[mid]' and orders='$orders'")->find();
        M('signson')->where("tsid='$temson[id]' and fid ='$id'")->save(array('issign'=>2));
        $newnode = $signinfo['nownode'] + 1;
        $save['nownode'] = $newnode;
        if($newnode ==  ($signinfo['totalnode'] + 1)){
            $save['status'] = 2;
            $htarr = M('sign')->where("id='$id'")->find();
            if($htarr['htid']>0){
                M('senduid')->where("id='$htarr[htid]'")->save(array('isno'=>'1'));
            }

        }

        $save['savetime'] = time();
        M('sign')->where("id='$id'")->save($save);
        //$this->deldir('./linshi/');
        if($newnode ==  ($signinfo['totalnode'] + 1)){
            $useridarr = M('sign')->where("id='$id'")->find();
            $userinfo_di = M("customer")->where("id='$useridarr[uid]'")->find();
            $minfo =  M("template")->where("id='$useridarr[mid]'")->find();
            // dump($userinfo_di);

            $this->sendMes($userinfo_di['dduserid'],'{"msgtype":"text","text":{"content":"您发起【'.$minfo['name'].'】的任务已经全部签署完毕"}}');
            // exit;
        }else{
            $nextorder = $orders + 1;
            $signinfo_di = M('sign')->where("id='$id'")->find();
            $minfo =  M("template")->where("id='$signinfo_di[mid]'")->find();
            $useridarr = M('templateson')->where("fid='$signinfo_di[mid]' and orders = '$nextorder' ")->find();
            $userinfo_di = M("customer")->where("id='$useridarr[uid]'")->find();
            $sj = date("Y-m-d H:i:s");
            $this->sendMes($userinfo_di['dduserid'],'{"msgtype":"text","text":{"content":"您有一个【'.$minfo['name'].'】任务文件待签署,上一轮签署时间为:'.$sj.'"}}');
           // dump($userinfo_di);
        }

        $rebak['code'] = 1;
        $rebak['msg'] = '成功';
        echo json_encode($rebak);
    }

    public function deldir($path){
        //如果是目录则继续
        if(is_dir($path)){
            //扫描一个文件夹内的所有文件夹和文件并返回数组
            $p = scandir($path);
            foreach($p as $val){
                //排除目录中的.和..
                if($val !="." && $val !=".."){
                    //如果是目录则递归子目录，继续操作
                    if(is_dir($path.$val)){
                        //子目录中操作删除文件夹和文件
                        deldir($path.$val.'/');
                        //目录清空后删除空文件夹
                        @rmdir($path.$val.'/');
                    }else{
                        //如果是文件直接删除
                        unlink($path.$val);
                    }
                }
            }
        }
    }
    /**
     * 图片处理合成
     */
    public function img_save($id,$arr,$pw,$ph,$orders=''){
        $userid = $_SESSION['index_userinfo'];
        $list = M('signimg')->where("sid='$id'")->select();
        foreach($list as $key=>$value){
            $imgarr[$key] = $value['img'];
        }
        $nowsign = M('customer')->where("id='$userid[userid]'")->find();
        $signinfo = M('sign')->where("id='$id'")->find();
        $sign_type_arr = M("templateson")->where("uid='$userid[userid]' and fid='$signinfo[mid]' and orders='$orders'")->find();
        if($sign_type_arr['type'] == 1){
            $nowsignname = $nowsign['signname'];
        }else{
            $signseal = M("seal")->where("id='$nowsign[sid]'")->find();
            $nowsignname = $signseal['img'];

        }

        $datename = './Public/timeimg/time.png';
        $image = new \Think\Image();
        $bottom = dirname(dirname(dirname(dirname(__FILE__))));

        foreach($arr as $key=>$value){

            $dqcztp = $imgarr[$key];
            $oldimg = $imgarr[$key];
            $image_file = ".".$imgarr[$key];
            $basedir   = './images/'.$userid['userid'].'/'.date('Y-m-d',time());
            $basedir2   = '/images/'.$userid['userid'].'/'.date('Y-m-d',time());
            $xzfile = md5(date('YmdHis').rand(1000, 9999)).'.'.'png';
            $newage_file =$basedir.$xzfile;
            $exif = exif_read_data($image_file);//获取exif信息
            if (isset($exif['Orientation']) && $exif['Orientation'] == 6) {
                $this->flip($image_file,$newage_file,'-90');
                $dqcztp = $basedir2.$xzfile;
            }


            //是否存在签名
            if($value['sign_left'] != '-1' && $value['date_left'] != '-1' ){
                //获取图片的实际宽高
                $nowimginfo = getimagesize('.'.$dqcztp);
                $nowinfowith = $nowimginfo[0];//图片的实际宽度
                $nowinfoheight = $nowimginfo[1];//图片的实际高度
                //计算合成距离
                $wz[0] = $value['sign_left'] * ($nowinfowith / $pw );//X轴距离
                $wz[1] = $value['sign_top'] * ($nowinfoheight / $ph);//Y轴距离
                $tw = $value['sign_width'] * ($nowinfowith / $pw );
                $th = $value['sign_height'] * ($nowinfoheight / $ph);
                //日期
                $r_wz[0] = $value['date_left'] * ($nowinfowith / $pw );
                $r_wz[1] = $value['date_top'] * ($nowinfoheight / $ph);
                $r_w = $value['date_width'] * ($nowinfowith / $pw );
                $r_h = $value['date_height'] * ($nowinfoheight / $ph );
                //生成临时的签名文件
                $nowname = 'qm'.time().$key.rand(1,10000).'.png';
                $image->open('.'.$nowsignname)->thumb($tw,$th)->save('./linshi/'.$nowname);
                //生成临时的日期文件
                $nowdatename = 'rq'.time().$key.rand(1,10000).'.png';
                $image->open($datename)->thumb($r_w,$r_h)->save('./linshi/'.$nowdatename);


                $basedir   = './images/'.$userid['userid'].'/'.date('Y-m-d',time());
                $creat_dir = 'images/'.$userid['userid'].'/'.date('Y-m-d',time());
                $photo = '/'.md5(date('YmdHis').rand(1000, 9999)).'.'.'png';
                $newFile = $basedir.$photo;
                if(!is_dir($creat_dir)){
                    mkdir($creat_dir,0777,true);
                }


                $image->open('.'.$dqcztp)->water('./linshi/'.$nowname,$wz,100)->water('./linshi/'.$nowdatename,$r_wz,100)->save($newFile);
                unlink('linshi/'.$nowdatename);
                unlink('linshi/'.$nowname);

                /*copy('linshi/'.$nowyes,$newFile);*/
                $yesarr[$key] = $newFile;
                M('signimg')->where("img='$oldimg'")->delete();
                //unlink($bottom."/".$imgarr[$key]);

            }else if($value['sign_left'] != '-1' && $value['date_left'] == '-1'){
                $nowimginfo = getimagesize('.'.$dqcztp);
                $nowinfowith = $nowimginfo[0];//图片的实际宽度
                $nowinfoheight = $nowimginfo[1];//图片的实际高度
                //计算合成距离
                $wz[0] = $value['sign_left'] * ($nowinfowith / $pw );//X轴距离
                $wz[1] = $value['sign_top'] * ($nowinfoheight / $ph);//Y轴距离
                $tw = $value['sign_width'] * ($nowinfowith / $pw );
                $th = $value['sign_height'] * ($nowinfoheight / $ph);
                //生成临时的签名文件
                $nowname = 'qm'.time().$key.rand(1,10000).'.png';
                $image->open('.'.$nowsignname)->thumb($tw,$th)->save('./linshi/'.$nowname);

                $basedir   = './images/'.$userid['userid'].'/'.date('Y-m-d',time());
                $creat_dir = 'images/'.$userid['userid'].'/'.date('Y-m-d',time());
                $photo = '/'.md5(date('YmdHis').rand(1000, 9999)).'.'.'png';
                $newFile = $basedir.$photo;
                if(!is_dir($creat_dir)){
                    mkdir($creat_dir,0777,true);
                }

                $image->open('.'.$dqcztp)->water('./linshi/'.$nowname,$wz,100)->save($newFile);
                unlink('linshi/'.$nowname);

                $yesarr[$key] = $newFile;
                M('signimg')->where("img='$oldimg'")->delete();
               // unlink($bottom."/".$imgarr[$key]);
            }else if($value['sign_left'] == '-1' && $value['date_left'] != '-1'){
                //获取图片的实际宽高
                $nowimginfo = getimagesize('.'.$dqcztp);
                $nowinfowith = $nowimginfo[0];//图片的实际宽度
                $nowinfoheight = $nowimginfo[1];//图片的实际高度

                //日期
                $r_wz[0] = $value['date_left'] * ($nowinfowith / $pw );
                $r_wz[1] = $value['date_top'] * ($nowinfoheight / $ph);
                $r_w = $value['date_width'] * ($nowinfowith / $pw );
                $r_h = $value['date_height'] * ($nowinfoheight / $ph );

                //生成临时的日期文件
                $nowdatename = 'rq'.time().$key.rand(1,10000).'.png';
                $image->open($datename)->thumb($r_w,$r_h)->save('./linshi/'.$nowdatename);

                $basedir   = './images/'.$userid['userid'].'/'.date('Y-m-d',time());
                $creat_dir = 'images/'.$userid['userid'].'/'.date('Y-m-d',time());
                $photo = '/'.md5(date('YmdHis').rand(1000, 9999)).'.'.'png';
                $newFile = $basedir.$photo;
                if(!is_dir($creat_dir)){
                    mkdir($creat_dir,0777,true);
                }
                $image->open('.'.$dqcztp)->water('./linshi/'.$nowdatename,$r_wz,100)->save($newFile);

               /* copy('linshi/'.$nowyes,$newFile);*/
                $yesarr[$key] = $newFile;
                M('signimg')->where("img='$oldimg'")->delete();
                //unlink($bottom."/".$imgarr[$key]);
            }else{
                $yesarr[$key] = $dqcztp;
                M('signimg')->where("img='$oldimg'")->delete();
            }
        }

        foreach($yesarr as $key=>$value){
            $insert['img'] = '/'.$value;
            $insert['sid'] = $id;
            M('signimg')->add($insert);
            $returnidarr[$key] = M('signimg')->getLastInsID();
        }

        return implode(',',$returnidarr);
    }


    public  function flip($filename,$src,$degrees = 90)
    {
        //读取图片
        $data = @getimagesize($filename);

        if($data==false)return false;
        //读取旧图片
        switch ($data[2]) {
            case 1:
                $src_f = imagecreatefromgif($filename);break;
            case 2:
                $src_f = imagecreatefromjpeg($filename);break;
            case 3:
                $src_f = imagecreatefrompng($filename);break;
        }
        if($src_f=="")return false;
        $rotate = @imagerotate($src_f, $degrees,0);
        if(!imagejpeg($rotate,$src,100))return false;
        @imagedestroy($rotate);
        return true;
    }


    //自然人提交上传图片
    public function zrrtj(){
        $user = $_SESSION["index_userinfo"];
        $img = $_POST['img'];
        $receiveuid = $_POST['receiveuid'];
        $title = $_POST['title'];
        $add['title'] = $title;
        $add['addtime'] = time();
        $add['senduid'] = $user['userid'];
        $add['receiveuid'] = $receiveuid;
        M('senduid')->add($add);
        $sid = M('senduid')->getLastInsID();
        $imgarr = explode(',',$img);
        foreach($imgarr as $key=>$value){
            $info = M('linshi')->where("id='$value'")->find();
            $insert['img'] = $info['img'];
            $insert['sid'] = $sid;
            M('sendimg')->add($insert);
            M('linshi')->where("id='$value'")->delete();
        }
        $rebak['code'] = 1;
        $rebak['msg'] = '成功';
        echo json_encode($rebak);
    }
    //自然人撤回操作
    public function cehui(){
        $id = $_POST['id'];
        $signnameurl = $this->web.$this->churl.$this->cs."/htid/".$id;
        echo curl_request($signnameurl);
    }
    //专管员受理列表
    public function zgysllist(){
       $this->display();
    }

    public function getzgylist(){
        $page = $_REQUEST['page'];
        $type = $_REQUEST['type'];
        $wsurl = $this->web.$this->zgylisturl.$this->cs."/page/".$page."/type/".$type;
        //echo $wsurl;
        $data  =  curl_request($wsurl);
        echo $data;
    }
    //专管员未受理列表
    public function zgywsllist(){
        $this->display();
    }
    //专管员发起签署
    public function zgysigndo(){
        $htid = $_REQUEST['htid'];
        $wsurl = $this->web.$this->zgy_sendsignurl.$this->cs.'/htid/'.$htid;
        $xgdata  =  curl_request($wsurl);
        $xgdataarr = json_decode($xgdata,true);
        if($xgdataarr['code']==2){
            $this->_redirect();
        }
        $idstr = $xgdataarr['imgstr'];
        $idarr = explode(',',$idstr);
        $id = $idarr[0];
        if(isset($_REQUEST['mid'])){
            $minfo = M('template')->where("id='$_REQUEST[mid]'")->find();
            $wsurl = $this->web.$this->mbxqurl.$this->cs.'/id/'.$_REQUEST['mid'];
            $data  =  curl_request($wsurl);
            $rebak = json_decode($data,true);
            if($rebak['code']==2){
                $this->_redirect();
            }
            $this->assign('list',$rebak['data']);
            $this->assign('minfo',$minfo);
        }
        $info = M('sendimg')->where("id='$id'")->find();
        $this->assign('htid',$htid);
        $this->assign('info',$info);
        $this->assign('time',time());
        $this->assign('count',count($idarr));
        $this->assign('id',$idstr);
        $this->assign('worker',$_SESSION["index_userinfo"]['woker']);

        //选择签名列表
        $signnameurl = $this->web.$this->signnameurl.$this->cs;
        $signdata  =  curl_request($signnameurl);
        $signbak   = json_decode($signdata,true);
        $this->assign('first',$signbak['first']);
        $this->assign('signnamelist',$signbak['data']);
        $this->assign('people',$signbak['people']);
        //选择盖章
        $sealnameurl = $this->web.$this->sealnameurl.$this->cs;
        $sealdata =   curl_request($sealnameurl);
        $sealrebak = json_decode($sealdata,true);
        $this->assign('seallist',$sealrebak['data']);
        //dump($signbak);

        $this->display();
    }
    //拒签
    public function jq(){
        $id = $_REQUEST['id'];
        $orders = $_REQUEST['orders'];
        $this->assign('id',$id);
        $this->assign('orders',$orders);
        $this->display();
    }
    //确定拒签
    public function jqdo(){
        $id = $_POST['id'];
        $orders = $_POST['orders'];
        $remark = $_REQUEST['content'];
        $sealnameurl = $this->web.$this->jqurl.$this->cs."/id/".$id."/orders/".$orders."/remark/".$remark;
        //echo $sealnameurl;
        $signinfo = M("sign")->where("id='$id'")->find();
        $userinfo_di = M("customer")->where("id='$signinfo[uid]'")->find();
        $minfo = M("template")->where("id='$signinfo[mid]'")->find();
        $sj = date("Y-m-d H:i:s");
        $this->sendMes($userinfo_di['dduserid'],'{"msgtype":"text","text":{"content":"您发起【'.$minfo['name'].'】的任务已经被拒签\n拒签时间为\n'.$sj.'"}}');
        echo curl_request($sealnameurl);
    }


    public function sendMes($userid_list,$msg,$dept_id_list='',$to_all_user='')
    {
        $token = $this->getToken();
        $mes_url="https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2?access_token=$token";
        $mes_data=array();
        $mes_data['agent_id']=$this->AgentId;
        $mes_data['userid_list']=$userid_list;
        /*$mes_data['dept_id_list']=$dept_id_list;
        $mes_data['to_all_user']=$to_all_user;*/
        $mes_data['msg']=$msg;
        $mes=httpPostRequest($mes_url, $mes_data);
        $mes_arr=json_decode($mes,true);
        return $mes_arr;
    }

    public function getToken()
    {

        //$token_url='https://oapi.dingtalk.com/gettoken?appkey=dingmiifst5ytplbcx6c&appsecret=5sbzI6K57qPyto3mlcZ4lwpJSenQjuuQBThFGBrcGA-j50rN-w_0YQFSnF1jc_Ua';
        $token_url='https://oapi.dingtalk.com/gettoken?appkey='.$this->AppKey.'&appsecret='.$this->AppSecret;
        $token_data=httpGetRequest($token_url);

        $token_arr=json_decode($token_data,true);
        $token=$token_arr['access_token'];

        return $token;
    }

}