<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Api\Controller;
class SendController extends BaseController
{
    /**
     * 审核文件页面
     */
    public function index(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $type   = $_REQUEST['type'];
        $page   = $_REQUEST['page'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $list = M('senduid')->where("status='$type' and senduid ='$userid'")->field("id,title,addtime,status,isno,nodescript,isout")->limit($start,10)->select();
            foreach($list as $key=>$value){
                $list[$key]["lefttime"] = $this->time_tran(date("Y-m-d,H:i:s", $value['addtime']));
            }
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['data'] = $list;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';

        }
        echo json_encode($rebak);
    }

    /**
     * 组织机构
     */
    public function zzjg(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $title  = $_REQUEST['title'];
        $yz = $this->getdbToken($userid,$token);
        empty($title) ? $where = ' and 1 ' : $where =" and username like '%$title%'";
        if($yz==1){
            $data = M('customer')->where("isbest='1' and status=1  $where")->field('id,username,worker,tel,topimage')->select();
            //echo M('customer')->getLastSql();
            foreach($data as $key=>$value){
                $data[$key]['topimage'] = C('web').$value['topimage'];
            }
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            $rebak['data'] = $data;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 上传页面
     */
    public function upload(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $receiveuid  = $_REQUEST['receiveuid'];
        $title  = $_REQUEST['title'];
        $yz = $this->getdbToken($userid,$token);

        if(empty($receiveuid)){
            $rebak['code'] =3;
            $rebak['msg'] = '请选择专管员';
            echo  json_encode($rebak);
            die;
        }
       if(empty($_FILES)){
            $rebak['code'] =4;
            $rebak['msg'] = '上传文件不能为空';
            echo  json_encode($rebak);
            die;
        }
        if(empty($title)){
            $rebak['code'] =5;
            $rebak['msg'] = '请填写合同名称';
            echo  json_encode($rebak);
            die;
        }
        if($yz==1){

            $bottom = dirname(dirname(dirname(dirname(__FILE__))));
            $path   = 'sendimages/'.$userid.'/'.date('Ymd',time());

            if (!file_exists ( $path )) {
                $this->mkdirs($path);
            }

            $add['title'] = $title;
            $add['addtime'] = time();
            $add['senduid'] = $userid;
            $add['receiveuid'] = $receiveuid;
            M('senduid')->add($add);
            $sid = M('senduid')->getLastInsID();
           foreach($_FILES['img']['tmp_name'] as $key=>$value){
                 $tmp_name = $value;
                 $savename = md5(rand(1000,9999).time()).".png";
                 $uploadfile = $bottom.'/'.$path.'/'.$savename;
                 $insert['img'] = '/'.$path.'/'.$savename;
                 $insert['sid'] = $sid;
                 M('sendimg')->add($insert);
                 move_uploaded_file($tmp_name, $uploadfile);
             }

             $rebak['code'] = 1;
             $rebak['msg'] = '成功';
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    public function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        if (!$this->mkdirs(dirname($dir), $mode)) return FALSE;
        return @mkdir($dir, $mode);
    }

    /**
     * 撤回操作
     */
    public function ch(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $htid  = $_REQUEST['htid'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            M('senduid')->where("id='$htid'")->save(array('isout'=>1));
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

}