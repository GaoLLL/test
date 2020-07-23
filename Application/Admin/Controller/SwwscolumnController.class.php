<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class SwwscolumnController extends BaseController
{
    public function index(){
        empty($_REQUEST['tel']) ? $where = '1' : $where ="name = '$_REQUEST[tel]'";
        $list = M('swwscolumn')->where($where)->select();
        $this->assign('list',$list);
        $this->assign('search',$_REQUEST);
        $this->display();
    }

    public function add(){
        if(IS_POST) {
            M('swwscolumn')->add($_POST);
            $rebak['status'] = 1;
            $rebak['msg'] = '添加成功';
            echo json_encode($rebak);
        }else{
            $this->display();
        }

    }

    public function save(){
        if(IS_POST) {
            M('swwscolumn')->where("id='$_POST[id]'")->save($_POST);
            $rebak['status'] = 1;
            $rebak['msg'] = '修改成功';
            echo json_encode($rebak);
        }else{
            $info = M('swwscolumn')->where("id='$_REQUEST[id]'")->find();

            $this->assign('info',$info);
            $this->display();
        }

    }

    public function del(){
       // M('swwscolumn')->where("fid='$_POST[id]'")->delete();
        $this->_del('swwscolumn',$_POST['id']);

    }

}