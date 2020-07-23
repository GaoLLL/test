<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Admin\Controller;


class UsersController extends BaseController
{
    public function index(){
        isset($_REQUEST['tel']) && !empty($_REQUEST['tel']) ? $where="tel='$_REQUEST[tel]' and status=1" : $where='status=1';
        $where.=" and usertype = 2";
        $list = $this->_list('customer',$where);
        $this->assign('search',$_REQUEST);
        foreach($list as $key=>$value){
            $ues = M('framework')->where("id='$value[pid]'")->find();
            $list[$key]['pidname'] = $ues['node_name'];
        }

        $this->assign("list",$list);
        $this->display();
    }


    public function zrrindex(){
        isset($_REQUEST['tel']) && !empty($_REQUEST['tel']) ? $where="tel='$_REQUEST[tel]' and status=1" : $where='status=1';
        $where.=" and usertype = 3";
        $list = $this->_list('customer',$where);
        $this->assign('search',$_REQUEST);
        foreach($list as $key=>$value){
            $ues = M('framework')->where("id='$value[pid]'")->find();
            $list[$key]['pidname'] = $ues['node_name'];
        }

        $this->assign("list",$list);
        $this->display();
    }


    public function nsrindex(){
        isset($_REQUEST['tel']) && !empty($_REQUEST['tel']) ? $where="tel='$_REQUEST[tel]' and status=1" : $where='status=1';
        $where.=" and usertype = 1";
        $list = $this->_list('customer',$where);
        $this->assign('search',$_REQUEST);
        foreach($list as $key=>$value){
            $ues = M('framework')->where("id='$value[pid]'")->find();
            $list[$key]['pidname'] = $ues['node_name'];
        }

        $this->assign("list",$list);
        $this->display();
    }

    public function add(){
        if(IS_POST){
            $res = M('customer')->add($_POST);
            if($res){
                $rebak['status'] = 1;
                $rebak['msg'] = '添加成功';
            }else{
                $rebak['status'] = 0;
                $rebak['msg'] = '添加失败';
            }
            echo  json_encode($rebak);
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
            //是否显示添加顶级组织按钮
            $count = M('framework')->where("type_id=0")->count();
            $count > 0 ? $show = 0 : $show = 1;
            $this->assign('show',$show);
            $this->display();
        }
    }


    public function seal(){
            $title = $_REQUEST['title'];
            isset($title) ? $where = " name like '%$title%' " : $where = 1;
            $idarr = M('customer')->where("sid<>0")->field("sid")->select();
            //$idarr = $this->_list('customer',"sid<>'0'",'sid');

            if(count($idarr)>0){
                $id = '';
                foreach($idarr as $key=>$value){
                    $id.=$value['sid'].',';
                }
                $id = rtrim($id,",");
                $where.=" and id not in($id)";
            }

            $list = $this->_list('seal',$where,'*','orders desc','',100);
            $this->assign('list',$list);
            $this->assign('search',$_REQUEST);
            $this->display();

    }

    public function save(){
        if(IS_POST){
            $this->_save('customer',$_POST,"id='$_POST[id]'");

        }else{
            $data = $this->_find('customer',"id=$_REQUEST[id]");
            $cj   = $this->_find('framework',"id='$data[pid]'");
            $seal = $this->_find('seal',"id='$data[sid]'");

            if($seal){
                $img = "<img src='$seal[img]' width='100' height='100' onclick='showimg(this)' style='cursor:pointer;'>";
            }else{
                $img = "";
            }

            $this->assign('img',$img);
            $this->assign('cj',$cj);
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function del(){
        $this->_del('customer',$_POST['id']);
    }

}