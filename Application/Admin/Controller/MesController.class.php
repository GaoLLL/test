<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;
use think\DB;


class MesController extends BaseController
{
    public function index(){
		//$listex = R('Api/Ding/getdepartmentList');
		//dump($listex);exit;
			
        if(IS_POST){
			$listex = R('Api/Ding/getdepartmentList');
			$list=$listex['department'];
			$all=array();
			$all[0]['title']='全部';
			$all[0]['id']='1';
			//dump($list);exit;
			foreach ($list as $k => $v) {              
					$list[$k]['title']=$list[$k]['name'];
					$bmid=$list[$k]['id'];
					$user=R('Api/Ding/getuserName',array($bmid,1,100));
					$userlist=$user["userlist"];
					foreach ($userlist as $l => $n) {
						$userlist[$l]['title']=$userlist[$l]['name'];
						$userlist[$l]['id']=$userlist[$l]['userid'];
					}
					$list[$k]['children']=$userlist;
					$all[$k+1]=$list[$k];
            }
            echo json_encode($all);
        }else{
            //是否显示添加顶级组织按钮
            $count = M('framework')->where("type_id=0")->count();
            $count > 0 ? $show = 0 : $show = 1;
            $this->assign('show',$show);
            $this->display();
        }
    }

    public function add(){
		//dump($_POST);exit;
		
		if($_POST['type']==0){
			$uidall=json_decode($_POST['uidall'],true);
			$bmuid=array();
			$mes=$_POST['txt'];
			if($uidall[0]['name']=="全部"){
				
				$add=R('Api/Ding/sendMes',array($mes,"",true));
				if($add['errcode']==0){
				$task_id=$add['task_id'];
				$data=[];
				$data['task_id']=$task_id;
				$indb=DB::name('mes')->save($data);
					$this->success('信息发送成功!');
				}else{
					$this->error('信息发送失败!');
				}
			}else{
				foreach ($uidall as $k => $v) { 				
					$bmuid[$k]=$uidall[$k]['children'];
				}
				foreach ($bmuid as $key => $value) {
					foreach ($value as $k => $v) {
						$uid.=$value[$k]["id"].",";
					}
				}
				$uid=rtrim($uid,','); 
				$add=R('Api/Ding/sendMes',array($mes,$uid,false));
				if($add['errcode']==0){
				$task_id=$add['task_id'];
				$data=[];
				$data['task_id']=$task_id;
				$indb=DB::name('mes')->save($data);
					$this->success('信息发送成功!');
				}else{
					$this->error('信息发送失败!');
				}
				
			}
		}elseif($_POST['type']==2){
			$uidall=json_decode($_POST['uidall'],true);
			$bmuid=array();
			$msgurl=$_POST['url'];
			$picUrl=$_POST['urlimg'];
			$title=$_POST['urltitle'];
			$text=$_POST['urltext'];
			if($uidall[0]['name']=="全部"){
				
				$add=R('Api/Ding/sendMes2',array($msgurl,$picUrl,$title,$text,"",true));
				if($add['errcode']==0){
				$task_id=$add['task_id'];
				$data=[];
				$data['task_id']=$task_id;
				$indb=DB::name('mes')->save($data);
					$this->success('信息发送成功!');
				}else{
					$this->error('信息发送失败!');
				}
			}else{
				foreach ($uidall as $k => $v) { 				
					$bmuid[$k]=$uidall[$k]['children'];
				}
				foreach ($bmuid as $key => $value) {
					foreach ($value as $k => $v) {
						$uid.=$value[$k]["id"].",";
					}
				}
				$uid=rtrim($uid,','); 
				$add=R('Api/Ding/sendMes2',array($msgurl,$picUrl,$title,$text,$userid_list,false));
				if($add['errcode']==0){
				$task_id=$add['task_id'];
				$data=[];
				$data['task_id']=$task_id;
				$indb=DB::name('mes')->save($data);
					$this->success('信息发送成功!');
				}else{
					$this->error('信息发送失败!');
				}
			}
		}elseif(($_POST['type']==1)&&(!empty($_FILES))){
			$file=$_FILES['image']; 
			//获取文件名 
			$fileName=$file['name']; 
			$fileType=$file['type'];
			$fileSize=$file['size'];
			$mediaarr=R('Api/Ding/getupload',array($fileName,$fileSize,$fileType));
			$media_id=$mediaarr['media_id'];
			$uidall=json_decode($_POST['uidall'],true);
			$bmuid=array();
			$mes=$_POST['txt'];
			if($uidall[0]['name']=="全部"){
				
				$add=R('Api/Ding/sendMes3',array($media_id,"",true));
				if($add['errcode']==0){
				$task_id=$add['task_id'];
				$data=[];
				$data['task_id']=$task_id;
				$indb=DB::name('mes')->save($data);
					$this->success('信息发送成功!');
				}else{
					$this->error('信息发送失败!');
				}
			}else{
				foreach ($uidall as $k => $v) { 				
					$bmuid[$k]=$uidall[$k]['children'];
				}
				foreach ($bmuid as $key => $value) {
					foreach ($value as $k => $v) {
						$uid.=$value[$k]["id"].",";
					}
				}
				$uid=rtrim($uid,','); 
				$add=R('Api/Ding/sendMes3',array($media_id,$userid_list,false));
				if($add['errcode']==0){
				$task_id=$add['task_id'];
				$data=[];
				$data['task_id']=$task_id;
				$indb=DB::name('mes')->save($data);
					$this->success('信息发送成功!');
				}else{
					$this->error('信息发送失败!');
				}
			}
		}
    
	}

    
}