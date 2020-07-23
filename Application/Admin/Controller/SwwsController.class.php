<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class SwwsController extends BaseController
{
    public function index(){
        empty($_REQUEST['tel']) ? $where = '1' : $where ="title like '%$_REQUEST[tel]%'";

        $list = $this->_list('swws',$where,'*','id desc');
        foreach($list as $key=>$value){
            $new = M('swwscolumn')->where("id='$value[pid]'")->find();
            $list[$key]['name'] = $new['name'];
        }

        $this->assign('list',$list);
        $this->assign('search',$_REQUEST);
        $this->display();
    }

    public function add(){
        if(IS_POST) {
            M('swws')->add($_POST);
            $rebak['status'] = 1;
            $rebak['msg'] = '添加成功';
            echo json_encode($rebak);
        }else{
            $list = M('swwscolumn')->where(1)->select();
            $this->assign('list',$list);
            $this->display();
        }

    }

    public function save(){
        if(IS_POST) {
            M('swws')->where("id='$_POST[id]'")->save($_POST);
            $rebak['status'] = 1;
            $rebak['msg'] = '修改成功';
            echo json_encode($rebak);
        }else{
            $list = M('swwscolumn')->where(1)->select();
            $this->assign('list',$list);
            $info = M('swws')->where("id='$_REQUEST[id]'")->find();
            $this->assign('info',$info);
            $this->display();
        }

    }

    public function del(){
        // M('swwscolumn')->where("fid='$_POST[id]'")->delete();
        $this->_del('swws',$_POST['id']);

    }

}