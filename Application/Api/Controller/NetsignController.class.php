<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Api\Controller;


class NetsignController extends BaseController
{
    public function test(){
        for($i=8;$i<=16;$i++){
            for($kk=38;$kk<=40;$kk++){
                $data['fid'] = $i;
                $data['issign'] = rand(1,3);
                $data['tsid'] = $kk;
                M('signson')->add($data);
            }
        }
    }

    public function index(){

        $userid  = $_REQUEST['userid'];
        $token   = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if($yz == 1){
            //$mycountsql = "select count(*) as nums from templateson as a,sign b  where a.fid in(select mid from sign where status=1) and a.uid = '$userid' and a.orders = b.nownode group by mid ";
            //$my  = M()->query($mycountsql);
            $mycountsql = "select * from sign as so,

(select a.*,b.orders,b.uid from signson as a , templateson as b where b.uid = $userid and a.tsid = b.id and a.issign = 1 group by a.fid) as son where so.id = son.fid and so.status = 1 and so.nownode = son.orders";
           // echo $mycountsql;
            $my  = M()->query($mycountsql);
            //echo $mycountsql;
           /* $othersql = "select * from sign as c,
(select a.*,b.issign from templateson as a , signson as b where a.fid in (select mid from sign where status=1 and uid='$userid') and a.id = b.tsid and issign = 1 GROUP BY a.fid) as d where d.fid = c.id and c.nownode <> d.orders";
            $other    = M()->query($othersql);*/

            $mysignsql = "select * from sign where status=1 and uid='$userid'";

            $mysignlist= M()->query($mysignsql);

            $orthernum = 0;
            foreach($mysignlist as $key=>$value){
                $sonsql  = "select * from signson as a , templateson as b where a.tsid = b.id and a.fid='$value[id]' and b.orders='$value[nownode]'";
             // echo $sonsql."<br/>";
                $sonlist = M()->query($sonsql);
                //dump($sonlist);
                foreach($sonlist as $k=>$v){
                    if($v['uid']!=$userid){
                        $orthernum = $orthernum + 1;
                    }
                }
            }


            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            $rebak['mycount'] = count($my);//待我签署
            $rebak['othercount']   = $orthernum;//待他人签署
            $sendcount = M('senduid')->where("receiveuid = '$userid' and status = '2' and isout ='2'    ")->count();
            $rebak['sendcount']   = $sendcount;//待他人签署
            $newsignsql = "SELECT
	*
FROM
	(
		SELECT
			b. NAME,
			a.id,
			a.starttime,
			a.endtime,
	        a.savetime,
	        a.status
		FROM
			sign AS a,
			template AS b
		WHERE
			a.uid = '$userid'
		AND a.mid = b.id 
		UNION ALL
			SELECT
				c. NAME,
				d.id,
				d.starttime,
				d.endtime,
			    d.savetime,
			    d.status
			FROM
				(
					SELECT
						a.*, b. NAME
					FROM
						(
							SELECT
								fid,
								orders
							FROM
								templateson
							WHERE
								uid = '$userid'
							GROUP BY
								fid
						) AS a,
						template AS b
					WHERE
						a.fid = b.id
				) AS c
			, sign AS d where c.fid = d.mid 
	) e
GROUP BY
	id order by savetime desc
LIMIT 10";

            $list = M()->query($newsignsql);
            //$list = M('sign')->where("uid='$userid'")->order('savetime desc')->limit(10)->select();
            $newlist = array();
            /*dump($list);
            die;*/
            foreach($list as $key=>$value){
                $newlist[$key]['id'] = $value['id'];
                $newlist[$key]['starttime'] = date('Y-m-d H:i:s',$value['starttime']);
                $newlist[$key]['endtime']   = date('Y-m-d',$value['endtime']);

                if($value['endtime']<time() && $value['status']=='1'){
                    M('sign')->where("id='$value[id]'")->save(array('status'=>'4'));
                    $newlist[$key]['status'] = '4';
                }else{
                    $newlist[$key]['status'] = $value['status'];
                }
               // $res = M('template')->where("id='$value[mid]'")->find();
                $newlist[$key]['name'] = $value['name'];

            }
            $rebak['data'] = $newlist;

        }else if($yz == 2){
            $rebak['code'] = 2;
            $rebak['msg'] = 'token值不正确';
        }else{
            $rebak['code'] = 3;
            $rebak['msg'] = 'userid不能为空';
        }
        echo json_encode($rebak);
    }

    /**
     * 发起签署页面
     */
    public function sendsign(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['time'] = date('Y-m-d H:i:s');
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';

        }
        echo json_encode($rebak);
    }
    /**
     * 专管员发起签署页面
     */
    public function zgy_sendsign(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $htid   = $_REQUEST['htid'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $newlist = array();
            $newstr  = array();
            $list = M('sendimg')->where("sid='$htid'")->select();
            foreach($list as $key=>$value){
                $newlist[$key] = C('web').$value['img'];
                $newstr[$key] = $value['id'];
            }
            $str = implode(',',$newstr);
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['time'] = date('Y-m-d H:i:s');
            $rebak['img']  = $newlist;
            $rebak['imgstr']  = $str;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';

        }
        echo json_encode($rebak);
    }

    /**
     * 网签模板
     */
    public function templates(){

        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $name  = $_REQUEST['name'];
        $page   = $_REQUEST['page'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        empty($name) ? $where ="status=1" : $where = " name like '%$name%' and status=1";
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $count =  M('template')->where($where)->count();

            $rebak['count'] = ceil($count/10);
            $list = M('template')->where($where)->field("id,name")->limit($start,10)->order("id desc")->select();
            //echo M('template')->getLastSql();
            $rebak['data'] = $list;

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';

        }
        echo json_encode($rebak);
    }

    /**
     * 模板详情
     */
    public function templateinfo(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id     = $_REQUEST['id'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $data = M('template')->where("id='$id'")->find();
            $list = M('templateson')->where("fid='$id'")->order('orders asc')->select();
            $newlist = array();
            foreach($list as $key=>$value){
                $user = M('customer')->where("id='$value[uid]'")->find();
                if($value['type'] == 2){
                    $seal = M('seal')->where("id='$user[sid]'")->find();
                    $newlist[$key]['sealname'] = $seal['name'];
                    $newlist[$key]['typename'] ='印章';
                }else{
                    $newlist[$key]['sealname'] = '';
                    $newlist[$key]['typename'] ='签名';

                }
                $newlist[$key]['type'] = $value['type'];
                $newlist[$key]['username'] = $user['username'];
                $newlist[$key]['tel'] = $user['tel'];
                $newlist[$key]['orders'] = $value['orders'];
            }
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['name'] = $data['name'];
            $rebak['data'] = $newlist;

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 是否自动流转->否->选择印章
     */
    public function showseal(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $title  = $_REQUEST['title'];
        empty($title) ? $where =" 1 and " : $where =" b.name like '%$title%' and ";
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $sql = "select a.id as uid,b.* from customer as a,seal as b  where $where a.sid > 0 and a.sid = b.id";
            $list = M()->query($sql);
            $newlist = array();
            foreach($list as $key=>$value){
                $newlist[$key]['img'] = C('web').$value['img'];
                $newlist[$key]['uid']  = $value['uid'];
                $newlist[$key]['name']  = $value['name'];
            }
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['data'] = $newlist ;

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 添加签署人->签字列表
     */
    public function signname(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id = $_REQUEST['fid'];
        $title = $_REQUEST['title'];
        $yz = $this->getdbToken($userid,$token);
        $new = array();
        if($yz==1){
            $rebak['code'] = 1;
            $rebak['msg'] ="成功";
            $in = M('framework')->where("type_id=0")->field('id,node_name')->find();
            $rebak['first'] = $in['node_name'];
            if(empty($title)){
                if(empty($id)){
                    $rebak['isend'] = 0;
                    $list = M('framework')->where("type_id=66")->field('id,node_name')->select();
                    foreach($list as $k=>$v){
                        $new[$k]['username'] = $v['node_name'];
                        $new[$k]['uid'] = $v['id'];
                        $new[$k]['tel'] = '';
                        $new[$k]['type'] = '1';
                        $new[$k]['typename'] = '签名';
                        $new[$k]['sealname'] = '';
                        $new[$k]['orders'] = '';
                    }
                    $datas = M('customer')->where("pid=66 and status=1")->field('id,username,worker,tel,topimage')->select();

                    foreach($datas as $key=>$value){
                        $udatas[$key]['username'] = $value['username'];
                        $udatas[$key]['uid'] = $value['id'];
                        $value['tel'] == '' ? $udatas[$key]['tel'] = '' : $udatas[$key]['tel'] = $value['tel'];
                        $udatas[$key]['type'] = 1;
                        $udatas[$key]['typename'] = '签名';
                        $udatas[$key]['sealname'] = '';
                        $udatas[$key]['orders'] = '';
                        $udatas[$key]['worker'] = $value['worker'];
                        $udatas[$key]['topimage'] = C('web').$value['topimage'];
                    }
                    count($udatas) == 0 ? $rebak['people'] = array() :  $rebak['people'] = $udatas;

                    $rebak['data'] = $new;
                }else{
                    $list = M('framework')->where("type_id='$id'")->field('id,node_name')->select();
                    if(count($list)==0){
                        $rebak['isend'] = 1;
                        $newlist = M('customer')->where("pid='$id'")->select();
                        foreach($newlist as $k=>$v){
                            $new[$k]['username'] = $v['username'];
                            $new[$k]['uid'] = $v['id'];
                            $new[$k]['tel'] = $v['tel'];
                            $new[$k]['type'] = '1';
                            $new[$k]['typename'] = '签名';
                            $new[$k]['sealname'] = '';
                            $new[$k]['orders'] = '';
                            $new[$k]['worker'] = $v['worker'];
                            $new[$k]['topimage'] = C('web').$v['topimage'];
                        }
                        $datas = M('customer')->where("pid='$id' and status=1")->field('id,username,worker,tel,topimage')->select();
                        foreach($datas as $key=>$value){
                            $datas[$key]['topimage'] = C('web').$value['topimage'];
                        }
                        $rebak['people'] = array();
                        $rebak['data'] = $new;
                    }else{
                        $rebak['isend'] = 0;
                        foreach($list as $k=>$v){
                            $new[$k]['username'] = $v['node_name'];
                            $new[$k]['uid'] = $v['id'];
                            $new[$k]['tel'] = '';
                            $new[$k]['type'] = '1';
                            $new[$k]['typename'] = '签名';
                            $new[$k]['sealname'] = '';
                            $new[$k]['orders'] = '';
                        }
                        $datas = M('customer')->where("pid='$id' and status=1")->field('id,username,worker,tel,topimage')->select();
                        foreach($datas as $key=>$value){
                            $datas[$key]['topimage'] = C('web').$value['topimage'];
                            if($value['tel'] == ''){
                                $datas[$key]['tel'] = '';
                            }
                        }
                        $rebak['people'] = $datas;
                        $rebak['data'] = $new;
                    }

                }
            }else{
                $rebak['isend'] = 1;
                $newlist = M('customer')->where("username like '%$title%'")->select();
                foreach($newlist as $k=>$v){
                    $new[$k]['username'] = $v['username'];
                    $new[$k]['uid'] = $v['id'];
                    $new[$k]['tel'] = $v['tel'];
                    $new[$k]['type'] = '1';
                    $new[$k]['typename'] = '签名';
                    $new[$k]['sealname'] = '';
                    $new[$k]['orders'] = '';
                    $new[$k]['worker'] = $v['worker'];
                    $new[$k]['topimage'] = C('web').$v['topimage'];
                }

                $datas = M('customer')->where("pid='$id' and status=1")->field('id,username,worker,tel,topimage')->select();
                foreach($datas as $key=>$value){
                    $datas[$key]['topimage'] = C('web').$value['topimage'];
                    if($value['tel'] == ''){
                        $datas[$key]['tel'] = '';
                    }
                }
                $rebak['people'] = array();
                $rebak['data'] = $new;
            }
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }


    /**
     * 添加签署人->印章
     */
    public function seallist(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id     = $_REQUEST['id'];
        $page = $_REQUEST['page'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $newlist = array();
            if(!empty($id)){
                $list = M('customer')->where("id='$id'")->field('id,username,tel,sid')->order("id asc")->limit($start,10)->select();
                foreach($list as $key=>$value){
                    $newlist[$key]['uid'] = $value['id'];
                    $newlist[$key]['username'] = $value['username'];
                    $newlist[$key]['tel'] = $value['tel'];
                    $newlist[$key]['type'] = 2;
                    $newlist[$key]['typename'] = '印章';
                    $info = M('seal')->where("id='$value[sid]'")->find();
                    $newlist[$key]['sealname'] = $info['name'];
                    $newlist[$key]['orders'] = '';

                }
            }else{
                $newlist = array();
            }
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['data'] =  $newlist;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 我发起
     */
    public function mysign(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id     = $_REQUEST['id'];
        $yz = $this->getdbToken($userid,$token);
        if(empty($id)){
            $rebak['code'] = 3;
            $rebak['msg'] = '发起id不能为空';
            echo json_encode($rebak);
            die;
        }
        if($yz==1){

              $data = M('sign')->where("id='$id'")->find();
              $user = M('customer')->where("id='$data[uid]'")->find();
              $template = M('template')->where("id='$data[mid]'")->find();
              $sql = "select b.orders,c.username,c.tel,b.type,a.issign,c.sid from signson as a,templateson as b,customer as c where a.tsid = b.id and b.uid = c.id and a.fid='$id'";
              $list = M()->query($sql);
              foreach($list as $key=>$value){
                  if($value['type'] == 2){
                      $sealinfo = M('seal')->where("id='$value[sid]'")->find();
                      $list[$key]['sealname'] = $sealinfo['name'];
                  }else{
                      $list[$key]['sealname'] = '';
                  }
              }
              $rebak['code'] = 1;
              $rebak['msg']  = '成功';
              $rebak['myname'] = $user['username'];
              $rebak['topimage'] = C('web').$user['topimage'];
              $rebak['starttime'] = date("Y-m-d H:i:s",$data['starttime']);
              $rebak['templatename'] = $template['name'];
              $rebak['signstatus'] = $data['status'];
              $rebak['endtime'] = date("Y.m.d",$data['endtime']);
              $rebak['data'] =  $list;

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }

        echo json_encode($rebak);
    }

    /**
     * 签署文件->全部
     */
    public function signall(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        $page = $_REQUEST['page'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        $page == 1 || empty($page) ? $end = 10 : $end = $start + 10;
        if($yz==1) {
            $sql = "select * from (
select @last := '1' as mystatus,b.name,a.id,a.savetime,a.starttime,a.endtime,a.status from sign as a ,template as b  where a.uid='$userid' and a.mid = b.id UNION all select @last := '2' as mystatus,c.name,d.id,d.savetime,d.starttime,d.endtime,d.`status` from (
select a.*,b.name from (SELECT fid,orders FROM templateson WHERE uid = '$userid' GROUP BY fid) as a,template as b where a.fid=b.id) as c , sign as d where c.fid = d.mid ) e group by id
			order by savetime desc limit $start,10";

            $list = M()->query($sql);
            $newlist = array();
            foreach ($list as $key => $value) {
                $newlist[$key]['leftime'] = $this->time_tran(date("Y-m-d,H:i:s", $value['starttime']));
                $newlist[$key]['start_time'] = date("Y.m.d H:i:s", $value['starttime']);
                $newlist[$key]['end_time'] = date("Y.m.d", $value['endtime']);
                $newlist[$key]['name'] = $value['name'];
                $newlist[$key]['status'] = $value['status'];
                $newlist[$key]['id'] = $value['id'];
                $newlist[$key]['mystatus'] = $value['mystatus'];
                /*if($value['uid'] == $userid){
                    $newlist[$key]['mystatus'] = 1;
                }else{
                    $newlist[$key]['mystatus'] = 2;
                }*/
            }
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['data'] = $newlist;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }
    /**
     * 签署文件->我发起
     */
    public function mysignlist(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        $page = $_REQUEST['page'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        $page == 1 || empty($page) ? $end = 10 : $end = $start + 10;
        if($yz==1) {
            $sql  = "select b.name,a.id,a.starttime,a.endtime,a.status from sign as a ,template as b  where a.uid='$userid' and a.mid = b.id order by a.savetime desc limit $start,$end";
            $list = M()->query($sql);
            $newlist = array();
            foreach ($list as $key => $value) {
                $newlist[$key]['leftime'] = $this->time_tran(date("Y-m-d,H:i:s", $value['starttime']));
                $newlist[$key]['start_time'] = date("Y.m.d H:i:s", $value['starttime']);
                $newlist[$key]['end_time'] = date("Y.m.d", $value['endtime']);
                $newlist[$key]['name'] = $value['name'];
                $newlist[$key]['status'] = $value['status'];
                $newlist[$key]['id'] = $value['id'];
                $newlist[$key]['mystatus'] = 1;

            }
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['data'] = $newlist;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);

    }
    /**
     * 签署文件->我签署
     */
    public function waitmysign(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        $page = $_REQUEST['page'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        $page == 1 || empty($page) ? $end = 10 : $end = $start + 10;
        if($yz==1) {
            $sql = "select c.name,d.id,d.starttime,d.endtime,d.`status` from (
select a.*,b.name from (SELECT fid,orders FROM templateson WHERE uid = '$userid' GROUP BY fid) as a,template as b where a.fid=b.id) as c , sign as d where  c.fid = d.mid and c.orders = d.nownode
			order by d.savetime desc limit $start,$end";

            $list = M()->query($sql);
            $newlist = array();
            foreach ($list as $key => $value) {
                $newlist[$key]['leftime'] = $this->time_tran(date("Y-m-d,H:i:s", $value['starttime']));
                $newlist[$key]['start_time'] = date("Y.m.d H:i:s", $value['starttime']);
                $newlist[$key]['end_time'] = date("Y.m.d", $value['endtime']);
                $newlist[$key]['name'] = $value['name'];
                $newlist[$key]['status'] = $value['status'];
                $newlist[$key]['id'] = $value['id'];
                $newlist[$key]['mystatus'] = 2;

            }
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
            $rebak['data'] = $newlist;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 签署详情页面->签署按钮
     */
    public function signinfo(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id     = $_REQUEST['id'];
        $yz = $this->getdbToken($userid,$token);
        if(!empty($id)){
            if($yz==1) {
                $infosql = "select a.id,c.username,c.topimage,a.starttime,a.endtime,b.name,a.status from sign as a,template as b,customer as c where a.id = '$id' and a.uid= c.id and a.mid = b.id ";
                $info    = M()->query($infosql);
                $littlesql = "select c.sid,a.remark,a.issign,b.uid,b.orders,b.type,c.username,c.tel from signson as a ,templateson as b ,customer as c where a.fid = '$id' and a.tsid = b.id  and b.uid=c.id  order by orders asc";
                $newlist = array();

                $list = M()->query($littlesql);
                $newstatus = 0;
                foreach($list as $key=>$value){
                    if($value['issign'] == 3){
                        $newlist[] = $value;
                        break;
                    }else{
                        $newlist[] = $value;
                    }
                }
                foreach($newlist as $key=>$value){
                    $iinfo = M('seal')->where("id='$value[sid]'")->find();
                    if($iinfo){
                        $newlist[$key]['sealname'] = $iinfo['name'];
                    }else{
                        $newlist[$key]['sealname'] = "";
                    }
                    if($newstatus == 0){
                        if($value['issign'] == 1){
                            $newstatus = 1;
                        }
                    }else{
                        if($value['issign'] == 1){
                            $newlist[$key]['issign'] = '5';
                        }

                    }
                }
                if($info[0]['endtime'] <= time() && $info[0]['status'] == 1){
                    M('sign')->where("id='$id'")->save(array('status'=>4));
                    $info[0]['status'] = 4;
                }
                $rebak['code'] =1;
                $rebak['msg'] = '成功';
                $rebak['id'] = $info[0]['id'];
                $rebak['username'] = $info[0]['username'];
                $rebak['start_time'] = date("Y.m.d H:i:s", $info[0]['starttime']);
                $rebak['end_time'] = date("Y.m.d", $info[0]['endtime']);
                $rebak['topimage'] = C('web').$info[0]['topimage'];
                $rebak['name'] = $info[0]['name'];
                $rebak['status'] = $info[0]['status'];

                $rebak['data'] = $newlist;
            }else{
                $rebak['code'] = 2;
                $rebak['msg']  = 'token值不正确';
            }
        }else{
            $rebak['code'] = 3;
            $rebak['msg']  = '签署id不能为空';
        }

        echo json_encode($rebak);
    }

    /**
     * 预览
     */
    public function signlook(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id     = $_REQUEST['id'];

        $yz = $this->getdbToken($userid,$token);
        if(!empty($id)){
            if($yz==1) {
                $list = M('signimg')->where("sid='$id'")->select();
                foreach($list as $key=>$value){
                    $list[$key]['img'] = C('web').$value['img'];
                }
                $rebak['code'] =1;
                $rebak['msg'] = '成功';
                $rebak['data'] = $list;
            }else{
                $rebak['code'] = 2;
                $rebak['msg']  = 'token值不正确';
            }
        }else{
            $rebak['code'] = 3;
            $rebak['msg']  = '签署id不能为空';
        }

        echo json_encode($rebak);
    }

    /**
     * 详情页面->点击签署按钮
     */
    public function signshow(){

        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id     = $_REQUEST['id'];
        $type   = $_REQUEST['type'];
        $orders = $_REQUEST['orders'];

        $yz = $this->getdbToken($userid,$token);
        $signbtn = '';
        if(empty($type)){
            $rebak['code'] =4;
            $rebak['msg'] = '签署方式不能为空';
            echo  json_encode($rebak);
            die;
        }
        if(empty($orders)){
            $rebak['code'] =4;
            $rebak['msg'] = '操作步骤不能为空';
            echo  json_encode($rebak);
            die;
        }
        if(!empty($id)){
            if($yz==1) {
                if($type == 1){
                    $info = M('customer')->where("id='$userid'")->find();
                    if($info['signname']==''){
                        $signbtn = '';
                    }else{
                        $signbtn = C('web').$info['signname'];
                    }

                }else if($type==2){
                    $info = M('customer')->where("id='$userid'")->find();
                    if($info['sid']==0){
                        $signbtn = '';
                    }else{
                        $newinfo = M('seal')->where("id='$info[sid]'")->find();
                        $signbtn = C('web').$newinfo['img'];
                    }
                }
                $list = M('signimg')->where("sid='$id'")->select();
                foreach($list as $key=>$value){
                    $list[$key]['img'] = C('web').$value['img'];
                }
                $rebak['code'] =1;
                $rebak['msg'] = '成功';
                $rebak['orders'] = $orders;
                $rebak['signbtn'] = $signbtn;
                $rebak['sid'] = $id;
                $rebak['data'] = $list;


            }else{
                $rebak['code'] = 2;
                $rebak['msg']  = 'token值不正确';
            }
        }else{
            $rebak['code'] = 3;
            $rebak['msg']  = '签署id不能为空';
        }

        echo json_encode($rebak);
    }

    /**
     * 确定发起签署
     */
    public function sendsigndo(){

        if($_REQUEST['sendtype'] == 1) {

            $userid = $_REQUEST['userid'];
            $token = $_REQUEST['token'];
            $ismake = $_REQUEST['ismake'];//是否自动流转，1：是，2：否
            $mid = $_REQUEST['mid'];//模板id
            $endtime = $_REQUEST['endtime'];//结束时间
            $tempatename = $_REQUEST['tempatename'];//模板名称
            $templateson = $_REQUEST['templateson'];//模板操作流程
            $templateson = json_decode($templateson, true);
            $templateson = $templateson['templateson'];
            $htid        = $_REQUEST['htid'];
            $yz = $this->getdbToken($userid, $token);
            if ($yz == 1) {
                if ($ismake == 1) {
                    if (empty($mid)) {
                        $rebak['code'] = 9;
                        $rebak['msg'] = '模板id不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    $data['uid'] = $userid;
                    $data['mid'] = $mid;
                    $data['endtime'] = strtotime($endtime);
                    $data['starttime'] = time();
                    $data['savetime'] = time();
                    $total = M('templateson')->where("fid='$mid'")->select();
                    $data['totalnode'] = count($total);
                    $data['nownode'] = 1;
                    $data['status'] = 1;
                    M('sign')->add($data);
                    $nowsignid = M('sign')->getLastInsID();

                    foreach ($total as $key => $value) {
                        $data1['fid'] = $nowsignid;
                        $data1['issign'] = 1;
                        $data1['tsid'] = $value['id'];
                        $data1['remark'] = '';
                        M('signson')->add($data1);
                    }
                    if (empty($_FILES['img'])) {
                        $rebak['code'] = 8;
                        $rebak['msg'] = '签署文件不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    $bottom = dirname(dirname(dirname(dirname(__FILE__))));
                    $path = 'images/' . $userid . '/' . date('Ymd', time());

                    if (!file_exists($path)) {
                        $this->mkdirs($path);
                    }

                    $sidarr = array();
                    foreach ($_FILES['img']['tmp_name'] as $key => $value) {
                        $tmp_name = $value;
                        $savename = md5(rand(1000, 9999) . time()) . ".png";
                        $uploadfile = $bottom . '/' . $path . '/' . $savename;
                        $insert['img'] = '/' . $path . '/' . $savename;
                        $insert['sid'] = $nowsignid;
                        M('signimg')->add($insert);
                        $nowinsertid = M('signimg')->getLastInsID();
                        $sidarr[] = $nowinsertid;
                        move_uploaded_file($tmp_name, $uploadfile);
                    }
                    $sidimg = implode(',', $sidarr);
                    M("sign")->where("id='$nowsignid'")->save(array('img' => $sidimg));
                    $rebak['code'] = 1;
                    $rebak['msg'] = '成功';
                    $rebak['sid'] = $nowsignid;
                } else {
                    if (empty($tempatename)) {
                        $rebak['code'] = 3;
                        $rebak['msg'] = '模板名称不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    if (empty($templateson)) {
                        $rebak['code'] = 4;
                        $rebak['msg'] = '操作流程不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    $data2['name'] = $tempatename;
                    $data2['creattime'] = time();
                    $data2['status'] = 1;
                    $data2['totalnode'] = count($templateson);
                    $res = M('template')->add($data2);
                    $nowtemplateid = M('template')->getLastInsID();

                    foreach ($templateson as $key => $value) {
                        $data3['fid'] = $nowtemplateid;
                        if (empty($value['uid'])) {
                            $rebak['code'] = 5;
                            $rebak['msg'] = '操作流程中含有uid为空的情况，这样不正确';
                            $rebak['data'] = $templateson;
                            echo json_encode($rebak);
                            die;
                        }
                        $data3['uid'] = $value['uid'];
                        if (empty($value['orders'])) {
                            $rebak['code'] = 6;
                            $rebak['msg'] = '操作流程中含有orders为空的情况，这样不正确';
                            echo json_encode($rebak);
                            die;
                        }
                        $data3['orders'] = $value['orders'];
                        if (empty($value['type'])) {
                            $rebak['code'] = 7;
                            $rebak['msg'] = '操作流程中含有type为空的情况，这样不正确';
                            echo json_encode($rebak);
                            die;
                        }
                        $data3['type'] = $value['type'];
                        M('templateson')->add($data3);
                    }

                    $data['uid'] = $userid;
                    $data['mid'] = $nowtemplateid;
                    $data['endtime'] = strtotime($endtime);
                    $data['starttime'] = time();
                    $data['savetime'] = time();
                    $total = M('templateson')->where("fid='$nowtemplateid'")->select();
                    $data['totalnode'] = count($templateson);
                    $data['nownode'] = 1;
                    $data['status'] = 1;
                    $data['htid'] = $htid;
                    M('sign')->add($data);
                    $nowsignid = M('sign')->getLastInsID();

                    foreach ($total as $key => $value) {
                        $data1['fid'] = $nowsignid;
                        $data1['issign'] = 1;
                        $data1['tsid'] = $value['id'];
                        $data1['remark'] = '';
                        M('signson')->add($data1);
                    }
                    if (empty($_FILES['img'])) {
                        $rebak['code'] = 8;
                        $rebak['msg'] = '签署文件不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    $bottom = dirname(dirname(dirname(dirname(__FILE__))));
                    $path = 'images/' . $userid . '/' . date('Ymd', time());

                    if (!file_exists($path)) {
                        $this->mkdirs($path);
                    }

                    $sidarr = array();
                    foreach ($_FILES['img']['tmp_name'] as $key => $value) {
                        $tmp_name = $value;
                        $savename = md5(rand(1000, 9999) . time()) . ".png";
                        $uploadfile = $bottom . '/' . $path . '/' . $savename;
                        $insert['img'] = '/' . $path . '/' . $savename;
                        $insert['sid'] = $nowsignid;
                        M('signimg')->add($insert);
                        $nowinsertid = M('signimg')->getLastInsID();
                        $sidarr[] = $nowinsertid;
                        move_uploaded_file($tmp_name, $uploadfile);
                    }
                    $sidimg = implode(',', $sidarr);
                    M("sign")->where("id='$nowsignid'")->save(array('img' => $sidimg));
                    $rebak['code'] = 1;
                    $rebak['msg'] = '成功';
                    $rebak['sid'] = $nowsignid;
                }
                if(!empty($htid)){
                    M('senduid')->where("id='$htid'")->save(array('status'=>1));

                }
            } else {
                $rebak['code'] = 2;
                $rebak['msg'] = 'token值不正确';
            }
            echo json_encode($rebak);
        }else{

            $userid = $_REQUEST['userid'];
            $token = $_REQUEST['token'];
            $ismake = $_REQUEST['ismake'];//是否自动流转，1：是，2：否
            $mid = $_REQUEST['mid'];//模板id
            $endtime = $_REQUEST['endtime'];//结束时间
            $tempatename = $_REQUEST['tempatename'];//模板名称
            $templatesonf = $_REQUEST['templateson'];//模板操作流程
            $htid        = $_REQUEST['htid'];//合同id
            $templatesonarr = explode('|',$templatesonf);

            $newarr = array();
            foreach($templatesonarr as $key=>$value){
                $newarr = explode(',',$value);
                $templateson[$key]['uid'] = $newarr[0];
                $templateson[$key]['orders'] = $newarr[1];
                $templateson[$key]['type'] = $newarr[2];
            }
             $rebak['datas'] = $templateson;
             $rebak['data'] = $_REQUEST['templateson'];

            $yz = $this->getdbToken($userid, $token);
            if ($yz == 1) {
                if ($ismake == 1) {
                    if (empty($mid)) {
                        $rebak['code'] = 9;
                        $rebak['msg'] = '模板id不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    $data['uid'] = $userid;
                    $data['mid'] = $mid;
                    $data['endtime'] = strtotime($endtime);
                    $data['starttime'] = time();
                    $data['savetime'] = time();
                    $total = M('templateson')->where("fid='$mid'")->select();
                    $data['totalnode'] = count($total);
                    $data['nownode'] = 1;
                    $data['status'] = 1;
                    $data['htid'] = $htid;
                    M('sign')->add($data);
                    $nowsignid = M('sign')->getLastInsID();

                    foreach ($total as $key => $value) {
                        $data1['fid'] = $nowsignid;
                        $data1['issign'] = 1;
                        $data1['tsid'] = $value['id'];
                        $data1['remark'] = '';
                        M('signson')->add($data1);
                    }
                    if (empty($_FILES['img'])) {
                        $rebak['code'] = 8;
                        $rebak['msg'] = '签署文件不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    $bottom = dirname(dirname(dirname(dirname(__FILE__))));
                    $path = 'images/' . $userid . '/' . date('Ymd', time());

                    if (!file_exists($path)) {
                        $this->mkdirs($path);
                    }

                    $sidarr = array();
                    foreach ($_FILES['img']['tmp_name'] as $key => $value) {
                        $tmp_name = $value;
                        $savename = md5(rand(1000, 9999) . time()) . ".png";
                        $uploadfile = $bottom . '/' . $path . '/' . $savename;
                        $insert['img'] = '/' . $path . '/' . $savename;
                        $insert['sid'] = $nowsignid;
                        M('signimg')->add($insert);
                        $nowinsertid = M('signimg')->getLastInsID();
                        $sidarr[] = $nowinsertid;
                        move_uploaded_file($tmp_name, $uploadfile);
                    }
                    $sidimg = implode(',', $sidarr);
                    M("sign")->where("id='$nowsignid'")->save(array('img' => $sidimg));
                    $rebak['code'] = 1;
                    $rebak['msg'] = '成功';
                    $rebak['sid'] = $nowsignid;
                } else {
                    if (empty($tempatename)) {
                        $rebak['code'] = 3;
                        $rebak['msg'] = '模板名称不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    if (empty($templateson)) {
                        $rebak['code'] = 4;
                        $rebak['msg'] = '操作流程不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    $data2['name'] = $tempatename;
                    $data2['creattime'] = time();
                    $data2['status'] = 1;
                    $data2['totalnode'] = count($templateson);
                    $res = M('template')->add($data2);
                    $nowtemplateid = M('template')->getLastInsID();

                    foreach ($templateson as $key => $value) {
                        $data3['fid'] = $nowtemplateid;
                        if (empty($value['uid'])) {
                            $rebak['code'] = 5;
                            $rebak['msg'] = '操作流程中含有uid为空的情况，这样不正确';
                            $rebak['data'] = $templateson;
                            echo json_encode($rebak);
                            die;
                        }
                        $data3['uid'] = $value['uid'];
                        if (empty($value['orders'])) {
                            $rebak['code'] = 6;
                            $rebak['msg'] = '操作流程中含有orders为空的情况，这样不正确';
                            echo json_encode($rebak);
                            die;
                        }
                        $data3['orders'] = $value['orders'];
                        if (empty($value['type'])) {
                            $rebak['code'] = 7;
                            $rebak['msg'] = '操作流程中含有type为空的情况，这样不正确';
                            echo json_encode($rebak);
                            die;
                        }
                        $data3['type'] = $value['type'];
                        M('templateson')->add($data3);
                    }

                    $data['uid'] = $userid;
                    $data['mid'] = $nowtemplateid;
                    $data['endtime'] = strtotime($endtime);
                    $data['starttime'] = time();
                    $data['savetime'] = time();
                    $total = M('templateson')->where("fid='$nowtemplateid'")->select();
                    $data['totalnode'] = count($templateson);
                    $data['nownode'] = 1;
                    $data['status'] = 1;
                    M('sign')->add($data);
                    $nowsignid = M('sign')->getLastInsID();

                    foreach ($total as $key => $value) {
                        $data1['fid'] = $nowsignid;
                        $data1['issign'] = 1;
                        $data1['tsid'] = $value['id'];
                        $data1['remark'] = '';
                        M('signson')->add($data1);
                    }
                    if (empty($_FILES['img'])) {
                        $rebak['code'] = 8;
                        $rebak['msg'] = '签署文件不能为空';
                        echo json_encode($rebak);
                        die;
                    }
                    $bottom = dirname(dirname(dirname(dirname(__FILE__))));
                    $path = 'images/' . $userid . '/' . date('Ymd', time());

                    if (!file_exists($path)) {
                        $this->mkdirs($path);
                    }

                    $sidarr = array();
                    foreach ($_FILES['img']['tmp_name'] as $key => $value) {
                        $tmp_name = $value;
                        $savename = md5(rand(1000, 9999) . time()) . ".png";
                        $uploadfile = $bottom . '/' . $path . '/' . $savename;
                        $insert['img'] = '/' . $path . '/' . $savename;
                        $insert['sid'] = $nowsignid;
                        M('signimg')->add($insert);
                        $nowinsertid = M('signimg')->getLastInsID();
                        $sidarr[] = $nowinsertid;
                        move_uploaded_file($tmp_name, $uploadfile);
                    }
                    $sidimg = implode(',', $sidarr);
                    M("sign")->where("id='$nowsignid'")->save(array('img' => $sidimg));
                    $rebak['code'] = 1;
                    $rebak['msg'] = '成功';
                    $rebak['sid'] = $nowsignid;
                }
                if(!empty($htid)){
                    M('senduid')->where("id='$htid'")->save(array('status'=>1));
                }
            } else {
                $rebak['code'] = 2;
                $rebak['msg'] = 'token值不正确';
            }
            echo json_encode($rebak);

        }
    }


    /**
     * 操作签署文件页面->确定签署
     */
    public function signdo(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id     = $_REQUEST['id'];
        $orders = $_REQUEST['orders'];
        $yz = $this->getdbToken($userid,$token);
        if(empty($orders)){
            $rebak['code'] =5;
            $rebak['msg'] = '操作步骤不能为空';
            echo  json_encode($rebak);
            die;
        }
        if(empty($id)){
            $rebak['code'] =3;
            $rebak['msg'] = '签署id不能为空';
            echo  json_encode($rebak);
            die;
        }
        if(empty($_FILES)){
            $rebak['code'] =4;
            $rebak['msg'] = '上传文件不能为空';
            echo  json_encode($rebak);
            die;
        }
        if($yz==1){
            $bottom = dirname(dirname(dirname(dirname(__FILE__))));
            $path   = 'images/'.$userid.'/'.date('Ymd',time());

            if (!file_exists ( $path )) {
               $this->mkdirs($path);
            }
            $list = M('signimg')->where("sid='$id'")->select();
            foreach($list as $key=>$value){
                //unlink($bottom."/".$value['img']);
                M('signimg')->where("id='$value[id]'")->delete();
            }
            $sidarr = array();
            foreach($_FILES['img']['tmp_name'] as $key=>$value){
                $tmp_name = $value;
                $savename = md5(rand(1000,9999).time()).".png";
                $uploadfile = $bottom.'/'.$path.'/'.$savename;
                $insert['img'] = '/'.$path.'/'.$savename;
                $insert['sid'] = $id;
                M('signimg')->add($insert);
                $nowinsertid = M('signimg')->getLastInsID();
                $sidarr[] = $nowinsertid;
                move_uploaded_file($tmp_name, $uploadfile);
            }

            $sidstr = implode(',',$sidarr);
            M('sign')->where("id='$id'")->save(array('img'=>$sidstr));
            $signinfo =  M('sign')->where("id='$id'")->find();
            $temson = M('templateson')->where("fid='$signinfo[mid]' and orders='$orders'")->find();
            M('signson')->where("tsid='$temson[id]' and fid ='$id'")->save(array('issign'=>2));
            $newnode = $signinfo['nownode'] + 1;
            $save['nownode'] = $newnode;
            if($newnode ==  ($signinfo['totalnode'] + 1)){
                $save['status'] = 2;
                $htarr = M('sign')->where("id='$id'")->find();
                if($htarr['htid']>0){
                 M('senduid')->where("id='$htarr[htid]'")->save(array('isno'=>'1'));
                }
            }
                $save['savetime'] = time();
            M('sign')->where("id='$id'")->save($save);
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    public function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
        if (!$this->mkdirs(dirname($dir), $mode)) return FALSE;
        return @mkdir($dir, $mode);
    }

    /**
     * 操作签署文件页面->拒签
     */
    public function nosign(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $id     = $_REQUEST['id'];
        $orders = $_REQUEST['orders'];
        $remark = $_REQUEST['remark'];
        $yz = $this->getdbToken($userid,$token);
        if(empty($id)){
            $rebak['code'] =3;
            $rebak['msg'] = '签署id不能为空';
            echo  json_encode($rebak);
            die;
        }
        if(empty($orders)){
            $rebak['code'] =4;
            $rebak['msg'] = '操作步骤不能为空';
            echo  json_encode($rebak);
            die;
        }
        if($yz==1) {
            $info   = M('sign')->where("id='$id'")->find();
            $infoid = M('templateson')->where("fid='$info[mid]' and orders='$orders'")->find();
            M('signson')->where("tsid='$infoid[id]'")->save(array('issign'=>3,'remark'=>$remark));
            M('sign')->where("id='$id'")->save(array('status'=>3));
            $htarr = M('sign')->where("id='$id'")->find();
            if($htarr['htid']>0) {
                M('senduid')->where("id='$htarr[htid]'")->save(array('isno' => 2,'nodescript'=>$remark));
            }
            $rebak['code'] =1;
            $rebak['msg'] = '成功';
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }

        echo json_encode($rebak);
    }

    /**
     * 个人中心页面
     */
    public function myselfinfo(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $info = M('customer')->where("id='$userid'")->find();
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            $rebak['topimage'] = C('web').$info['topimage'];
            $rebak['username'] = $info['username'];
            $rebak['tel'] = $info['tel'];

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 我的签名
     */

    public function mysignname(){

        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $info = M('customer')->where("id='$userid'")->find();
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            if(empty($info['signname'])){
                $rebak['signname'] = '';
            }else{
                $rebak['signname'] = C('web').$info['signname'];
            }
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 修改我的签名
     */
    public function savemysign(){

        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if(empty($_FILES)){
            $rebak['code'] =3;
            $rebak['msg'] = '上传文件不能为空';
            echo  json_encode($rebak);
            die;
        }
        if($yz==1){
            $bottom = dirname(dirname(dirname(dirname(__FILE__))));
            $path   = 'signname/'.$userid.'/'.date('Ymd',time());

            if (!file_exists ( $path )) {
                $this->mkdirs($path);
            }

            $info = M('customer')->where("id='$userid'")->find();
            if($info['signname']!=''){
                unlink($bottom."/".$info['signname']);
            }

            foreach($_FILES['img']['tmp_name'] as $key=>$value){
                $tmp_name = $value;
                $savename = md5(rand(1000,9999).time()).".png";
                $uploadfile = $bottom.'/'.$path.'/'.$savename;
                move_uploaded_file($tmp_name, $uploadfile);
                M('customer')->where("id='$userid'")->save(array('signname'=>'/'.$path.'/'.$savename));
            }
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }

        echo json_encode($rebak);
    }

    /**
     * 删除我的签名
     */
    public function delmyself(){
        $bottom = dirname(dirname(dirname(dirname(__FILE__))));
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $info = M('customer')->where("id='$userid'")->find();
            $url = $bottom.$info['signname'];
            unlink($url);
            M('customer')->where("id='$userid'")->save(array('signname'=>''));
            $rebak['code'] = 1;
            $rebak['msg']  = '成功';
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 我的印章
     */
    public function myseal(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $info = M('customer')->where("id='$userid'")->find();
            $myseal = M('seal')->where("id='$info[sid]'")->find();

            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            if($info['sid']==0){
                $rebak['seal'] = '';
                $rebak['sealname'] = '';
            }else{
                $rebak['seal'] = C('web').$myseal['img'];
                $rebak['sealname'] = $myseal['name'];
            }
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 修改密码
     */
    public function uppwd(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $oldpwd = $_REQUEST['oldpwd'];
        $newpwd = $_REQUEST['newpwd'];
        $yz = $this->getdbToken($userid,$token);

        if($yz==1){
            $info = M('customer')->where("id='$userid'")->find();
            if($info['password'] != md5($oldpwd)){
                $rebak['code'] = 3;
                $rebak['msg'] = '原密码不正确';
            }else{
                M('customer')->where("id='$userid'")->save(array('password'=>md5($newpwd)));
                $rebak['code'] = 1;
                $rebak['msg'] = '成功';
            }

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 税务文书列表
     */
    public function swws(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $page   = $_REQUEST['page'];
        $pid    = $_REQUEST['pid'];
        $title  = $_REQUEST['title'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        $yz = $this->getdbToken($userid,$token);
        empty($title) ? $where = 1 : $where = "title like '%$title%'";
        if($yz==1){
            $list = M('swwscolumn')->where(1)->order("id asc")->select();

            empty($pid) && $pid = $list[0]['id'];
            $newlist = M('swws')->where("pid='$pid' and $where")->order("id desc")->limit($start,10)->select();
            foreach($newlist as $key=>$value){
                $yeslist[$key]['id'] = $value['id'];
                $yeslist[$key]['url'] = C('web').$value['img'];
                $yeslist[$key]['title'] = $value['title'];
            }
           // echo M('swws')->getLastSql();
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            $rebak['toplist'] = $list;
            $rebak['bottomlist'] = $yeslist;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);


    }

    /**
     * 税务文件详情
     */
    public function swwsinfo(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $id = $_REQUEST['id'];
            if(empty($id)){
                $rebak['code'] = 3;
                $rebak['msg'] = '文件id不能为空';
            }else{
                $info = M('swws')->where("id='$id'")->find();
                $rebak['code'] = 1;
                $rebak['msg'] = '成功';
                $rebak['data'] = C('web').$info['img'];
            }

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 组织架构图列表
     */
    public function zzjgtlist(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $title  = $_REQUEST['title'];
        $yz = $this->getdbToken($userid,$token);
        empty($title) ? $where = ' and 1 ' : $where =" and username like '%$title%'";
        if($yz==1){
           $pid = $_REQUEST['pid'];
           if(empty($pid)){
               $list = M('framework')->where("type_id='66'")->select();
               if(empty($title)){
                   $data = M('customer')->where("pid=66 and status=1 $where")->field('id,username,worker,tel,topimage')->select();
                   foreach($data as $key=>$value){
                       $data[$key]['topimage'] = C('web').$value['topimage'];
                   }
               }else{
                   $data = M('customer')->where("status=1 $where")->field('id,username,worker,tel,topimage')->select();
                   foreach($data as $key=>$value){
                       $data[$key]['topimage'] = C('web').$value['topimage'];
                   }
               }
           }else{
               $list = M('framework')->where("type_id='$pid'")->select();
               if(empty($title)){
                   $data = M('customer')->where("pid=$pid and status=1 $where ")->field('id,username,worker,tel,topimage')->select();

                   foreach($data as $key=>$value){
                       $data[$key]['topimage'] = C('web').$value['topimage'];
                   }
               }else{
                   $data = M('customer')->where(" status=1 $where ")->field('id,username,worker,tel,topimage')->select();

                   foreach($data as $key=>$value){
                       $data[$key]['topimage'] = C('web').$value['topimage'];
                   }
               }



           }
            $yes = M('framework')->where("type_id='0'")->find();
            $crumb['id'] = $yes['id'];
            $crumb['node_name'] = $yes['node_name'];

            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            $rebak['data'] = $list;
            $rebak['people'] = $data;

            $rebak['onename'] = $crumb;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 人员详情
     */
    public function userinfo(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $id = $_REQUEST['id'];
            if(empty($id)){
                $rebak['code'] = 3;
                $rebak['msg'] = '人员id不能为空';
            }else{
                $info = M('customer')->where("id='$id'")->find();
                $framework = M('framework')->where("id='$info[pid]'")->find();
                $data['id'] = $info['id'];
                $data['tel'] = $info['tel'];
                $data['topimage'] = C('web').$info['topimage'];
                $data['bm'] = $info['bm'];
                $data['framework'] = $framework['node_name'];
                $data['username'] = $info['username'];
                $data['worker'] = $info['worker'];
                $data['phone'] = $info['phone'];
                $data['small_tel'] = $info['small_tel'];
                $isqz = M('templateson')->where("uid='$id' and type=1")->count();
                if($isqz > 0 ){
                    $isqztrue = 1;
                }else{
                    $isqztrue = 0;
                }
                $isgz = M('templateson')->where("uid='$id' and type=2")->count();
                if($isgz > 0 ){
                    $isgztrue = 1;
                }else{
                    $isgztrue = 0;
                }
                if($isqztrue == 1 && $isgztrue == 1){
                    $data['have'] = '签字、印章';
                }else if($isqztrue == 0 && $isgztrue == 1){
                    $data['have'] = '印章';
                }else if($isqztrue == 1 && $isgztrue == 0){
                    $data['have'] = '签字';
                }else{
                    $data['have'] = '无';
                }
                $rebak['code'] = 1;
                $rebak['msg'] = '成功';
                $rebak['data'] = $data;
            }

        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 公告模块
     */
    public function bullhorn(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $page   = $_REQUEST['page'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $list = M('bullhorn')->where(1)->order('id desc')->limit($start,10)->select();
            foreach($list as $key=>$value){
                $list[$key]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
            }
            $rebak['code'] = 1;
            $rebak['msg'] ='成功';
            $rebak['data'] = $list;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 自然人申请专管员管理页面
     */
    public function nosignlist(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $page   = $_REQUEST['page'];
        $type   = $_REQUEST['type'];
        $page == 1 || empty($page) ? $start = 0 : $start = ($page-1) * 10;
        $yz = $this->getdbToken($userid,$token);
        if($yz==1){
            $list = M('senduid')->where("status = '$type' and receiveuid = '$userid' and isout = 2 ")->field("id,title,addtime,isno,status,nodescript")->order('id desc')->limit($start,10)->select();
            //echo M('senduid')->getLastSql();
            foreach($list as $key=>$value){
                $list[$key]['lefttime'] = $this->time_tran(date('Y-m-d H:i:s',$value['addtime']));
            }
            $rebak['code'] = 1;
            $rebak['msg'] ='成功';
            $rebak['data'] = $list;
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 安卓特殊上传接口
     */
    public function sub_az_upload(){
        $userid = $_REQUEST['userid'];
        $token  = $_REQUEST['token'];
        $yz = $this->getdbToken($userid,$token);
        if(empty($_FILES)){
            $rebak['code'] =4;
            $rebak['msg'] = '上传文件不能为空';
            echo  json_encode($rebak);
            die;
        }
        if($yz==1){
            $bottom = dirname(dirname(dirname(dirname(__FILE__))));
            $path   = 'bestaz/'.$userid.'/'.date('Ymd',time());
            if (!file_exists ( $path )) {
                $this->mkdirs($path);
            }
            foreach($_FILES['img']['tmp_name'] as $key=>$value){
                $tmp_name = $value;
                $savename = md5(rand(1000,9999).time()).".docx";
                $uploadfile = $bottom.'/'.$path.'/'.$savename;
                move_uploaded_file($tmp_name, $uploadfile);
            }
            $rebak['data'] = C('web').'/'.$path.'/'.$savename;
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
        }else{
            $rebak['code'] = 2;
            $rebak['msg']  = 'token值不正确';
        }
        echo json_encode($rebak);
    }
}