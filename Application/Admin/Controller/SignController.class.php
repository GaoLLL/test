<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class SignController extends BaseController
{
    public function index(){
        isset($_REQUEST['tel']) && !empty($_REQUEST['tel']) ? $where="name like'%$_REQUEST[tel]%' and status=1" : $where='status=1';
        $count = D('template')->where($where)->count();
        if(floor($_REQUEST['pagesize'])==$_REQUEST['pagesize'] && $_REQUEST['pagesize'] > 0){
            $pagesize = $_REQUEST['pagesize'];
        }else{
            $pagesize = 15;
        }
        $Page = new \Think\Page($count,$pagesize);
        $show = $Page->show();
        $list = M('template')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('pagesize',$pagesize);
        $this->assign('page',$show);
        $this->assign('count',$count);
        $this->assign('list',$list);
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $insert = $_POST['big'];
            M('template')->add(array('name'=>$_POST['name'],'status'=>1,'creattime'=>$_POST['creat_time'],'totalnode'=>count($insert)));
            $aid = M('template')->getLastInsID();

            foreach($insert as $key=>$value){

                $data['fid'] = $aid;
                $data['type'] = $value['type'];
                $u = M('customer')->where("sid='$value[uid]'")->find();
                if($value['type']=='2'){
                    $data['uid'] = $u['id'];
                }else{
                    $data['uid'] = $value['uid'];
                }
                $data['orders'] = $value['orders'];
                M('templateson')->add($data);
            }
            $rebak['status'] = 1;
            $rebak['msg'] = '添加成功';
            echo json_encode($rebak);
        }else{
            $this->display();
        }

    }

    public function choise(){
        if(IS_POST){
            $list = M('framework')->field("id,node_name name,type_id pid")->select();
            $node = getTree($list, false);
            echo json_encode(array('code'=>1,'data'=>$node,'msg'=>'ok'));
        }else{
            $title = $_REQUEST['title'];
            isset($title) ? $where = " name like '%$title%' and status=1" : $where = "status=1";
            $list = $this->_list('customer',$where,'*','id desc','',100);
            $this->assign('list',$list);
            $this->assign('nownode',$_REQUEST['nowid']);
            $this->assign('search',$_REQUEST);
            $this->display();
        }
    }

    public function seal(){
        $title = $_REQUEST['title'];
        isset($title) ? $where = " name like '%$title%' " : $where = 1;
        $idarr = $this->_list('customer',"sid<>0",'sid');
        if(count($idarr)>0){
            $id = '';
            foreach($idarr as $key=>$value){
                $id.=$value['sid'].',';
            }
            $id = rtrim($id,",");
            $where.=" and id in($id)";
        }

        $list = $this->_list('seal',$where,'*','orders desc','',100);
        $this->assign('list',$list);
        $this->assign('nownode',$_REQUEST['nowid']);
        $this->assign('search',$_REQUEST);
        $this->display();

    }

    public function save(){

       if(IS_POST){
           $id = $_POST['id'];
           $insert = $_POST['big'];
           M('template')->where("id='$id'")->save(array('name'=>$_POST['name'],'totalnode'=>count($insert)));
           M('templateson')->where("fid='$id'")->delete();

           foreach($insert as $key=>$value){
               $data['fid'] = $id;
               $data['type'] = $value['type'];
               $u = M('customer')->where("sid='$value[uid]'")->find();
               if($value['type']=='2'){
                   $data['uid'] = $u['id'];
               }else{
                   $data['uid'] = $value['uid'];
               }
               $data['orders'] = $value['orders'];
               M('templateson')->add($data);
           }
           $rebak['status'] = 1;
           $rebak['msg'] = '修改成功';
           echo json_encode($rebak);
       }else{

        $id = $_REQUEST['id'];
        $list = M('template')->where("id='$id'"  )->find();
        $now = M('templateson')->where("fid='$id'")->select();

        foreach($now as $key=>$value){
            $users = M('customer')->where("id='$value[uid]'")->find();
           // echo M('customer')->getLastSql()."<br/>";
            if($value['type'] == 1){
                $now[$key]['qm'] = "<span style='margin-left: 10px' ><b>".$users['username']."</b>&nbsp;&nbsp;<img src='".$users['signname']."' width='100' height='30' onclick='showimg(this)' style='cursor:pointer;'></span>";
            }else{
                $t = M('seal')->where("id='$users[sid]'")->find();
                $now[$key]['qm'] = "<span style='margin-left: 10px' ><b>".$users['username']."</b>&nbsp;&nbsp;<img src='".$t['img']."' width='100' height='30' onclick='showimg(this)' style='cursor:pointer;'></span>";
            }
        }

        $list['big'] = $now;
        $list['count'] = count($now);
        $this->assign('data',$list);
        $this->display();
       }
    }

    public function del(){
        M('templateson')->where("fid='$_POST[id]'")->delete();
        $this->_del('template',$_POST['id']);

    }

}