<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class BullhornController extends BaseController
{
    public function index(){
        empty($_REQUEST['title']) ? $where = '1' : $where ="title like '%$_REQUEST[title]%'";
        $list = $this->_list('bullhorn',$where);

        $this->assign('list',$list);
        $this->assign('search',$_REQUEST);
        $this->display();
    }

    public function add(){
        if(IS_POST) {
            M('bullhorn')->add($_POST);
            $rebak['status'] = 1;
            $rebak['msg'] = '添加成功';
            echo json_encode($rebak);
        }else{
            $this->display();
        }

    }

    public function save(){
        if(IS_POST) {
            M('bullhorn')->where("id='$_POST[id]'")->save($_POST);
            $rebak['status'] = 1;
            $rebak['msg'] = '修改成功';
            echo json_encode($rebak);
        }else{
            $info = M('bullhorn')->where("id='$_REQUEST[id]'")->find();
            $this->assign('data',$info);
            $this->display();
        }

    }

    public function del(){
        // M('swwscolumn')->where("fid='$_POST[id]'")->delete();
        $this->_del('bullhorn',$_POST['id']);

    }

}