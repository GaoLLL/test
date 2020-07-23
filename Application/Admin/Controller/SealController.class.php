<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class SealController extends BaseController
{

    public function index(){

        $list = $this->_list('seal',1,'*','orders desc');
        $this->assign('list',$list);
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $this->_add('seal',$_POST);
        }else{
            $this->display();
        }
    }

    public function save(){
        if(IS_POST){
            $data = $this->_find('seal',array('id'=>$_POST['id']));
            if($data['img'] !=$_POST['img']){
                unlink('.'.$data['img']);
            }
            $this->_save('seal',$_POST,array('id'=>$_POST['id']));
        }else{
            $data = $this->_find('seal',array('id'=>$_REQUEST['id']));
            $this->assign('data',$data);

            $this->display();
        }
    }

    public function del(){
        $data = $this->_find('seal',array('id'=>$_REQUEST['id']));
        unlink('.'.$data['img']);
        $this->_del('seal',$_POST['id']);

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
            $data = $this->_find('seal',array('id'=>$v));
            unlink('.'.$data['img']);
            $res = D('seal')->delete($v);

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