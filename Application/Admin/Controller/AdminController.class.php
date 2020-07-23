<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class AdminController extends BaseController
{
    public function index()
    {
        $where = $this->search();
        $where["type"] = array("neq",1);
        $count = D('admin')->where($where)->count();
        if(floor($_REQUEST['pagesize'])==$_REQUEST['pagesize'] && $_REQUEST['pagesize'] > 0){
            $pagesize = $_REQUEST['pagesize'];
        }else{
            $pagesize = 15;
        }
        $Page = new \Think\Page($count,$pagesize);
        $show = $Page->show();
        $list = D('admin')->where($where)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
        $group = new GroupController();
        $grouplist = $group->getGroupList();
        foreach($list as $k=>$v){
            $list[$k]["group"] = $this->getValueByKey($grouplist,$v["group_id"]);
        }
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->assign('pagesize',$pagesize);
        $this->assign('count',$count);
        $this->display();
    }

    public function search(){
        $where = array();
        $search = array();
        $search["username"] = $this->checkGet("username");
        if($search["username"]){
            $where["username"] = array("like","%{$search["username"]}%");
        }

        $this->assign('search',$search);
        return $where;
    }

    public function log_view(){
        $count = D('admin_log')->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $list = D('admin_log')->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $k=>$v){
            $list[$k]['time'] = date("Y-m-d H:i:s",$v['time']);
        }
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }

    public function add(){
        if (IS_POST) {
            if(empty($_POST['username'])){
                ReturnJson(0,'账号不能为空');
            }else{
                $uname = $_POST['username'];
                if(!(Validate::isNames($_POST['username'], 5, 20))){
                    ReturnJson(0,'账号长度必须在5—20位');
                }else{
                    $count = D('admin')->where(" username = '$uname' ")->count();
                    if($count > 0){
                        ReturnJson(0,'账号已存在');
                    }
                }
            }
            if(empty($_POST['password1'])){
                ReturnJson(0,'密码不能为空');
            }
            if(!(Validate::isPWD($_POST['password1'], 5, 20))){
                ReturnJson(0,'密码长度必须在5—20位');
            }
            if($_POST['password1'] != $_POST['password2'] ){
                ReturnJson(0,'两次输入的密码不一致，请重新输入');
            }
            $pwd = I('password1','','md5');
            $data = array(
                'username'=>$_POST['username'],
                'password'=>$pwd,
                'type'=>2,
                'group_id'=>$_POST['group_id'],
            );
            $res = D('admin')->add($data);
            if($res !== false){
                $this->set_admin_log('系统管理员——新增：'.$_POST['username']);
                ReturnJson(1, 10007);
            }else {
                ReturnJson(0, 10008);
            }
        }else{
            $group = new GroupController();
            $this->assign("group",$group->getGroupList());
            $this->display();
        }
    }

    public function save(){
        if (IS_POST) {
            if(!empty($_POST['password'])){
                if(!(Validate::isPWD($_POST['password'], 5, 20))){
                    ReturnJson(0,'密码长度必须在5—20位');
                }else{
                    $pwd = I('password','','md5');
                    $data = array(
                        'id'=>$_POST['id'],
                        'password'=>$pwd,
                        'group_id'=>$_POST['group_id']
                    );
                }
            }else{
                $data = array(
                    'id'=>$_POST['id'],
                    'group_id'=>$_POST['group_id']
                );
            }
            $res = D('admin')->save($data);
            if($res !== false){
                $this->set_admin_log('系统管理员——修改：'.$_POST['id']);
                ReturnJson(1, 10009);
            }else {
                ReturnJson(0, 10010);
            }
        }else{
            $admin = D('admin')->find(intval($_GET['id']));
            $this->assign('admin',$admin);
            $group = new GroupController();
            $this->assign("group",$group->getGroupList());
            $this->display();
        }
    }

    //删除
    public function del()
    {
        $id = I('post.id', '0', 'intval');
        $deluser = D('admin')->find($id);
        if (D('admin')->delete($id) === false) {
            ReturnJson(0, 10012);
        } else {
            $this->set_admin_log('系统管理员——删除：'.$deluser['username']);
            ReturnJson(1, 10011);
        }
    }

    //删除选中
    public function alldel(){
        if(count($_POST['id']) < 1){
            ReturnJson(0, '最少要选中一个才能进行操作');
        }
        $ids = $_POST['id'];
        $model = D('users');
        $model->startTrans();
        $res = false;
        foreach($ids as $k=>$v) {
            $res = D('admin')->delete($v);
        }
        if ($res === false) {
            $model->rollback();
            ReturnJson(0, 10012);
        } else {
            $model->commit();
            $this->set_admin_log('系统管理员——删除选中：'.implode(",",$ids));
            ReturnJson(1, 10011);
        }
    }
}