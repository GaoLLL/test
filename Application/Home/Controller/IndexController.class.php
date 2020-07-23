<?php

namespace Home\Controller;

use Think\Controller;
use Think\Verify;
class IndexController extends Controller
{

    protected $zcurl = "/api/login/register";
    protected $dlurl = "/api/login/index";
    protected $web   = '';

    private $AppKey     = '';
    private $AppSecret  = '';
    private $AgentId    = '';

    public function __construct()
    {
        parent::__construct();
        $this->web = C('web');
        $this->AppKey = C('AppKey');
        $this->AppSecret = C('AppSecret');
        $this->AgentId = C('AgentId');
    }

    /**
     * 登录
     */
    public function index()
    {
        $this->display('choice');
    }




    public function yzinfo(){
        $ddcode = $_REQUEST['ddcode'];
        //获取当前登陆钉钉用户id
        $rebakss    = $this->getuserinfos($ddcode);
        $dduserid   = $rebakss['userid'];
        if($rebakss['errcode']==0){
            //获取当前钉钉用户详情信息
           $userinfo =  $this->getuser($dduserid);
           if($userinfo['errcode'] == 0){
                //通过手机号获取数据库中用户信息
               $res  = M('customer')->where("tel='$userinfo[mobile]'")->find();
               if($res){

                   $data['token']  = $this->getToken();
                   M('customer')->where("id='$res[id]'")->save(array('token'=>$data['token']));
                   $data['userid'] = $res['id'];
                   $data['woker']  = $res['username'];
                   $data['idnumber']  = $res['idnumber'];
                   if($data['sign']==''){
                       $data['signname']  = '';
                   }else{
                       $data['signname']  = C('web').$res['signname'];
                   }

                   if($res['sid']!=''){
                       $result = M('seal')->where("id='$res[sid]'")->find();
                       $data['seal'] = C('web').$result['img'];
                   }else{
                       $data['seal'] = '';
                   }
                   //判断是否为自然人
                   if($res['usertype']==1){
                       $data['usertype'] = 1;//纳税人
                   }else if($res['usertype']==2){
                       //判断是否为专管员
                       if($res['isbest']==1){
                           $data['usertype'] = 2;//专管员
                       }else{
                           $data['usertype'] = 3;//正常税务人员
                       }
                   }else{
                       $data['usertype'] = 3;//自然人
                   }

                   if($res['dduserid']==''){
                       M("customer")->where("id='$res[id]'")->save(array("dduserid"=>$dduserid));
                   }
                   $_SESSION['index_userinfo'] = $data;

                   $rebak['code'] = 1;
                   $rebak['msg'] = '登录成功';

               }else{
                   $rebak['code'] = 0;
                   $rebak['msg'] = '没有权限，请联系管理员，开通帐号';
               }

           }else{
               $rebak['code'] = 0;
               $rebak['msg'] = $rebakss['errmsg'];
           }


        }else{
            $rebak['code'] = 0;
            $rebak['msg'] = $rebakss['errmsg']."请退出，请先进入网签系统";

        }

        echo json_encode($rebak);
        die;

    }


    public function getuser($user_id)
    {   //$user_id = $_REQUEST['user_id'];
        $token = $this->getToken();
       // $user_url="https://oapi.dingtalk.com/user/get?access_token=".$token."&user_id=".$user_id;
        $user_url = "https://oapi.dingtalk.com/user/get?access_token=".$token."&userid=".$user_id;
        $user_data=httpGetRequest($user_url);
        $user_arr=json_decode($user_data,true);
       // dump($user_data);
        return $user_arr;
    }

    // public function getusers()
    // {   $user_id = $_REQUEST['user_id'];
    //     $token = $this->getToken();
    //     $user_url = "https://oapi.dingtalk.com/user/get?access_token=".$token."&userid=17390705411180864";
    //    // $user_url = "https://oapi.dingtalk.com/user/simplelist?access_token=".$token."&department_id=13126933";
    //    // $user_url ="https://oapi.dingtalk.com/department/list?access_token=".$token;//部门
    //     //$user_url="https://oapi.dingtalk.com/user/get?access_token=".$token."&user_id=".$user_id;
    //     $user_data=httpGetRequest($user_url);
    //     $user_arr=json_decode($user_data,true);
    //      dump($user_data);
    //     return $user_arr;
    // }


    //获取access_token
    public function getToken()
    {

        //$token_url='https://oapi.dingtalk.com/gettoken?appkey=dingmiifst5ytplbcx6c&appsecret=5sbzI6K57qPyto3mlcZ4lwpJSenQjuuQBThFGBrcGA-j50rN-w_0YQFSnF1jc_Ua';
        $token_url='https://oapi.dingtalk.com/gettoken?appkey='.$this->AppKey.'&appsecret='.$this->AppSecret;
        $token_data=httpGetRequest($token_url);

        $token_arr=json_decode($token_data,true);
        $token=$token_arr['access_token'];

        return $token;
    }

    public function getuserinfos($code)
    {

        $token = $this->getToken();
        //dump($token);
        //$code="b35c332477bc3873b7950dffe00092f2";
        $url="https://oapi.dingtalk.com/user/getuserinfo?access_token=".$token."&code=".$code;

        $data=httpGetRequest($url);
        $arr=json_decode($data,true);
       // dump($arr);
        return $arr;

    }


    public function login(){
        if(IS_POST){
            $data  =  curl_request($this->web.$this->dlurl,$_POST);
            $rebak = json_decode($data,true);
            $rebak['code'] == 1 && $_SESSION['index_userinfo'] = $rebak['data'];
            echo $data;
        }else{
            $this->assign("usertypes",$_REQUEST['type']);
            $this->display('login');
        }
    }

    /**
     * 注册用户
     */
    public function reg(){
        if(IS_POST){
            if($this->check_verify($_POST['code'])){
               echo  httpPostRequest($this->web.$this->zcurl,$_POST);

            }else{
                $rebak['code'] = 3;
                $rebak['msg'] = '验证码不正确';
                echo json_encode($rebak);
                die;
            }
        }else{
            switch ($_REQUEST['usertype']){
                case 0:
                    $usertype = 2;
                    $username = '姓名';
                    $idnumber = '身份证号';
                    break;
                case 1:
                    $usertype = 1;
                    $username = '法人';
                    $idnumber = '纳税识别号';
                    break;
                case 2:
                    $usertype = 3;
                    $username = '姓名';
                    $idnumber = '身份证号';
                    break;
            }
            $this->assign('username',$username);
            $this->assign('idnumber',$idnumber);
            $this->assign('usertype',$usertype);
            $this->display('registered');
        }
    }

    /* 生成验证码 */
     public function verify()
     {
         $config = array(
             'fontSize' => 19, // 验证码字体大小
             'length' => 4, // 验证码位数
             'imageH' => 34,
             'useCurve'=>false,
             'useNoise'=>false,
         );
         $Verify = new Verify($config);
         $Verify->entry();
     }

    /* 验证码校验 */
    public function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        $res = $verify->check($code, $id);
        return $res;
        //$this->ajaxReturn($res, 'json');
    }


    /*function getimgs($str)
    {
        $reg = '/((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(=jpg|=gif|=png|=jpeg)/';

        $matches = array();
        preg_match_all($reg, $str, $matches);

        dump($matches);
    }*/
}