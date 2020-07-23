<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class FrameworkController extends BaseController
{
    public function index(){

        if(IS_POST){
            $list = M('framework')->field("id,node_name name,type_id pid")->select();
            $node = getTree($list, false);

            echo json_encode(array('code'=>1,'data'=>$node,'msg'=>'ok'));
        }else{
            //是否显示添加顶级组织按钮
            $count = M('framework')->where("type_id=0")->count();
            $count > 0 ? $show = 0 : $show = 1;
            $this->assign('show',$show);
            $this->display();
        }
    }

    public function add(){
        M('framework')->add($_POST);
        echo  json_encode(array('code'=>1, 'data'=>'', 'msg'=>'添加成功'));
    }

    public function save(){
        M('framework')->where("id='$_POST[id]'")->save($_POST);
        echo  json_encode(array('code'=>1, 'data'=>'', 'msg'=>'修改成功'));
    }

    public function del(){
        M('framework')->where("id='$_POST[id]'")->delete();
        echo  json_encode(array('code'=>1, 'data'=>'', 'msg'=>'删除成功'));
    }
}