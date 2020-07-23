<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:50
 */

namespace Admin\Controller;


class SettingController extends BaseController
{
    public function index()
    {
        $where = $this->search();
        $where["type"] = array("neq",1);
        $count = D('error')->where($where)->count();
        if(floor($_REQUEST['pagesize'])==$_REQUEST['pagesize'] && $_REQUEST['pagesize'] > 0){
            $pagesize = $_REQUEST['pagesize'];
        }else{
            $pagesize = 15;
        }
        $Page = new \Think\Page($count,$pagesize);
        $show = $Page->show();
        $list = D('error')->where($where)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->assign('pagesize',$pagesize);
        $this->assign('count',$count);
        $this->display();
    }

    public function search(){
        $where = array();
        $search = array();
        $search["error_id"] = $this->checkGet("error_id");
        if($search["error_id"]){
            $where["error_id"] = array("like","%{$search["error_id"]}%");
        }

        $this->assign('search',$search);
        return $where;
    }

    public function add()
    {
        if (IS_POST) {
            $res = D('error')->add($_POST);
            if ($res !== false) {
                $this->set_admin_log('信息设置——新增：'.$_POST['error_detail']);
                ReturnJson(1, 10007);
            } else {
                ReturnJson(0, 10008);
            }
        }else{
            $this->display();
        }
    }

    public function save(){
        if (IS_POST) {
            $res = D('error')->save($_POST);
            if ($res !== false) {
                $this->set_admin_log('信息设置——修改：'.$_POST['error_detail']);
                ReturnJson(1, 10009);
            } else {
                ReturnJson(0, 10010);
            }
        }else{
            $info = D('error')->find(intval($_GET['id']));
            $this->assign('info', $info);
            $this->display();
        }
    }

    //删除
    public function del()
    {
        $id = I('post.id', '0', 'intval');
        $del = D('error')->find($id);
        if (D('error')->delete($id) === false) {
            ReturnJson(0, 10012);
        } else {
            $this->set_admin_log('信息设置——删除：'.$del['error_detail']);
            ReturnJson(1, 10011);
        }
    }

    //删除选中
    public function alldel(){
        if(count($_POST['id']) < 1){
            ReturnJson(0, '最少要选中一个才能进行操作');
        }
        $ids = $_POST['id'];
        $model = D('error');
        $model->startTrans();
        $res = false;
        foreach($ids as $k=>$v) {
            $res = D('error')->delete($v);
        }
        if ($res === false) {
            $model->rollback();
            ReturnJson(0, 10012);
        } else {
            $model->commit();
            $this->set_admin_log('信息设置——删除选中：'.implode(",",$ids));
            ReturnJson(1, 10011);
        }
    }
}