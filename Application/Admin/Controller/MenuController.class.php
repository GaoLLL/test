<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:50
 */

namespace Admin\Controller;


class MenuController extends BaseController
{
    public function index()
    {
        $where = $this->search();
        $where["level"] = array("neq",0);
        $count = D('model')->where($where)->count();
        if(floor($_REQUEST['pagesize'])==$_REQUEST['pagesize'] && $_REQUEST['pagesize'] > 0){
            $pagesize = $_REQUEST['pagesize'];
        }else{
            $pagesize = 999;
        }
        $Page = new \Think\Page($count,$pagesize);
        $show = $Page->show();
        $list = D('model')->where($where)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
        $list = $this->tree($list);
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
            $res = D('model')->add($_POST);
            if ($res !== false) {
                $this->set_admin_log('菜单管理——新增：'.$_POST['name']);
                ReturnJson(1, 10007);
            } else {
                ReturnJson(0, 10008);
            }
        }else{
            $list = D('Model')->where('level!=0')->select();
            $list = $this->tree($list);
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function save()
    {
        if (IS_POST) {
            $res = D('model')->save($_POST);
            if ($res !== false) {
                $this->set_admin_log('菜单管理——修改：'.$_POST['name']);
                ReturnJson(1, 10009);
            } else {
                ReturnJson(0, 10010);
            }
        }else{
            $info = D('model')->find(intval($_GET['id']));
            $this->assign('info', $info);
            $list = D('Model')->where('level!=0')->select();
            $list = $this->tree($list);
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function del()
    {
        $id = I('post.id', '0', 'intval');
        $del = D('model')->find($id);
        if (D('model')->delete($id) === false) {
            ReturnJson(0, 10012);
        } else {
            $this->set_admin_log('菜单管理——删除：'.$del['name']);
            ReturnJson(1, 10011);
        }
    }

    public function tree($array, $parent = 0, $nubmer = 0)
    {
        static $arr = array();
        if (is_array($array)) {
            foreach ($array as $v) {
                if ($v['belongid'] == $parent) {
                    $v['number'] = $nubmer;
                    $arr[$v['id']] = $v;
                    $this->tree($array, $v['id'], $nubmer + 1);
                }

            }
            return $arr;
        }
    }
}