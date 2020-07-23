<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class GroupController extends BaseController
{
    public function index()
    {
        $where = $this->search();
        $count = D('group')->where($where)->count();
        if(floor($_REQUEST['pagesize'])==$_REQUEST['pagesize'] && $_REQUEST['pagesize'] > 0){
            $pagesize = $_REQUEST['pagesize'];
        }else{
            $pagesize = 15;
        }
        $Page = new \Think\Page($count,$pagesize);
        $show = $Page->show();
        $list = D('group')->where($where)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->assign('pagesize',$pagesize);
        $this->assign('count',$count);
        $this->display();
    }

    public function search(){
        $where = array();
        return $where;
    }

    public function getGroupList(){
        $group = D('group')->select();
        $arr = array();
        foreach($group as $v){
            $arr[$v["id"]] = $v["name"];
        }
        return $arr;
    }

    public function rbac()
    {
        if (IS_POST) {
            $id = I('post.id');
            foreach ($_POST['data'] as $item => $value) {
                $model = D('Model')->find(intval($value['id']));
                $arr = explode(',', $model['group_id']);
                if ($value['checked'] == 'true') {
                    if (!in_array($id, $arr)) {//当被选中，但是不存在的时候加入id进去
                        $data['group_id'] = trim($model['group_id'] . ',' . $id, ',');
                        $data['id'] = $value['id'];
                        D('model')->save($data);
                    }
                } else {
                    if (in_array($id, $arr)) {//当没有选中，但是存在的时候去掉id
                        unset($arr[array_search($id, $arr)]);
                        $data['group_id'] = implode(',',$arr);
                        $data['id'] = $value['id'];
                        D('model')->save($data);
                    }
                }
            }
            ReturnJson(1, 10006);
        } else {
            $str = '';
            $id = I('get.id');
            $list = D('model')->where('level!=0')->select();
            foreach ($list as $key => $v) {
                $arr = explode(',', $v['group_id']);
                $checked = in_array($id, $arr) ? 'true' : 'false';
                $str .= "{'id':'" . $v['id'] . "','belongid':'" . $v['belongid'] . "','name':'" . $v['name'] . "','checked':'" . $checked . "','open':true,'group_id':'" . $v['group_id'] . "'},";
            }
            $this->assign('str', trim($str, ','));
            $this->display();
        }
    }

    public function add(){
        if (IS_POST) {
            $data = array('name'=>$_POST['name']);
            $res = D('group')->add($data);
            if($res !== false){
                $this->set_admin_log('权限组——新增：'.$_POST['name']);
                ReturnJson(1, 10007);
            }else {
                ReturnJson(0, 10008);
            }
        }else{
            $this->display();
        }
    }

    public function save(){
        if (IS_POST) {
            $data = array('id'=>$_POST['id'], 'name'=>$_POST['name']);
            $res = D('group')->save($data);
            if($res !== false){
                $this->set_admin_log('权限组——修改：'.$_POST['name']);
                ReturnJson(1, 10009);
            }else {
                ReturnJson(0, 10010);
            }
        }else{
            $info = D('group')->find(intval($_GET['id']));
            $this->assign('info',$info);
            $this->display();
        }
    }

    //删除
    public function del()
    {
        $id = I('post.id', '0', 'intval');
        $deluser = D('group')->find($id);
        if (D('group')->delete($id) === false) {
            ReturnJson(0, 10012);
        } else {
            $this->set_admin_log('权限组——删除：'.$deluser['name']);
            ReturnJson(1, 10011);
        }
    }
}