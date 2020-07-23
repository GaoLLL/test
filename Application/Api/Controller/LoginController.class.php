<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */

namespace Api\Controller;
class LoginController extends BaseController
{


    protected $getdduserid = "/api/ding/getuserinfo";
    protected $web;

    private $AppKey = '';
    private $AppSecret = '';
    private $AgentId = '';

    public function __construct()
    {
        $this->AppKey = C('AppKey');
        $this->AppSecret = C('AppSecret');
        $this->AgentId = C('AgentId');
    }
    public function getToken()
    {

        $token_url='https://oapi.dingtalk.com/gettoken?appkey='.$this->AppKey.'&appsecret='.$this->AppSecret;
        
        $token_data=$this->httpGetRequest($token_url);

        $token_arr=json_decode($token_data,true);
        $token=$token_arr['access_token'];

        return $token;
    }

    public function getuserinfo($code)
    {

        $token = $this->getToken();
        //$code="b35c332477bc3873b7950dffe00092f2";
        $url="https://oapi.dingtalk.com/user/getuserinfo?access_token=".$token."&code=".$code;
        $data=$this->httpGetRequest($url);
        $arr=json_decode($data,true);
        return $arr;

    }


    /**
     * 登录接口
     */
    public function index(){

        $tel = $_REQUEST['tel'];
        $password = md5($_REQUEST['password']);
        $res = M('customer')->where("tel='$tel' and password='$password'")->find();
        //echo M('customer')->getLastSql();
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
            $data['sid'] = $res['sid'];
            $rebak['code'] = 1;
            $rebak['msg'] = '成功';
            $rebak['data'] = $data;
        }else{
            $rebak['code'] = 0;
            $rebak['msg'] = '账号或者密码不正确';
        }
        echo json_encode($rebak);
    }

    /**
     * 注册接口
     */
    public function register(){

       $this->web  =  C('web');
       $tel = $_REQUEST['tel'];
       $username = $_REQUEST['username'];
       $idnumber = $_REQUEST['idnumber'];
       $password = $_REQUEST['password'];
       $usertype = $_REQUEST['usertype'];
       $ddcode   = $_POST['ddcode'];
        /*$rebak['msg'] = $ddcode;
        echo json_encode($rebak);
        die;*/

       //$ddcode   = '62c568ed86833e129f449899d4a9c3ce';

        /*$rebak['code'] = 0;
        $rebak['msg'] = $rebakss['userid'];
        echo json_encode($rebak);
        die;*/

       if($usertype == 1){
           $res  =  M('customer')->where("idnumber='$idnumber'")->count();
           if($res>0){
               $rebak['code'] = 3;
               $rebak['msg'] = '该身份证已经注册过，请换其他的身份证号';
               echo json_encode($rebak);
               die;
           }
           $res1 = M('customer')->where("tel='$tel'")->count();
           if($res1>0){
               $rebak['code'] = 2;
               $rebak['msg'] = '该手机号已经注册过，请换其他的手机号';
               echo json_encode($rebak);
               die;
           }
           //$rebakss = $this->httpGetRequest($this->web.$this->getdduserid."/code/".$ddcode);
           $rebakss = $this->getuserinfo($ddcode);
           $dduserid   = $rebakss['userid'];
           $data['tel'] = $tel;
           $data['username'] = $username;
           $data['idnumber'] = $idnumber;
           $data['password'] = md5($password);
           $data['creat_time'] = time();
           $data['usertype'] = $usertype;
           $data['dduserid'] = $dduserid;
           $result = M('customer')->add($data);
           if($result){
               $rebak['code'] = 1;
               $rebak['msg'] = '注册成功';
           }else{
               $rebak['code'] = 0;
               $rebak['msg'] = '请联系后台操作人员';
           }
           echo json_encode($rebak);
       }else{
           $res =  M('customer')->where("idnumber='$idnumber'")->find();
          // echo M('customer')->getLastSql();
           if($res){
               if($res['tel']==$tel){
                   $rebak['code'] = 2;
                   $rebak['msg'] = '该手机号已经注册过，请换其他的手机号';
               }else{
                   //$rebakss = $this->httpGetRequest($this->web.$this->getdduserid."/code/".$ddcode);
                   $rebakss = $this->getuserinfo($ddcode);
                   $dduserid   = $rebakss['userid'];
                   $data['tel'] = $tel;
                   $data['username'] = $username;
                   $data['idnumber'] = $idnumber;
                   $data['password'] = md5($password);
                   $data['creat_time'] = time();
                   $data['usertype'] = $usertype;
                   $data['dduserid'] = $dduserid;
                   $result = M('customer')->where("id='$res[id]'")->save($data);

                   if($result){
                       $rebak['code'] = 1;
                       $rebak['msg'] = '注册成功';
                   }else{
                       $rebak['code'] = 0;
                       $rebak['msg'] = '请联系后台操作人员';
                   }
               }
           }else{
               $rebak['code'] = 0;
               $rebak['msg'] = '身份证无效,请联系后台管理员';
           }
           echo json_encode($rebak);
       }

    }

    /**
     * APP网签首页是否需要完善信息接口
     */
    public function appindex(){
        $id    = $_REQUEST['userid'];
        $token = $_REQUEST['token'];
        $res = M('customer')->where("id='$id'")->find();
        if($token==$res['token']){
            if($res['signname']==''){
                $rebak['code'] = 1;
                $rebak['msg'] = '需要';
            }else{
                $rebak['code'] = 0;
                $rebak['msg'] = '不需要';
            }
        }else{
            $rebak['code'] = 2;
            $rebak['msg'] = 'token值不正确';
        }

        echo json_encode($rebak);
    }


    /**
     * 函数的含义说明：CURL发送post请求    获取数据
     *
     * @access public
     * @param str          $url     发送接口地址
     * @param array/json   $data    要发送的数据
     * @param false/true   $json    false $data数组格式  true $data json格式
     * @return  返回json数据
     */
    function httpPostRequest($url, $data = null,$json = FALSE){
        //创建了一个curl会话资源，成功返回一个句柄；
        $curl = curl_init();
        //设置url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置为FALSE 禁止 cURL 验证对等证书（peer’s certificate）
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        //设置为 1 是检查服务器SSL证书中是否存在一个公用名(common name)
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            //设置请求为POST
            curl_setopt($curl, CURLOPT_POST, 1);
            //curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60); //最长的可忍受的连接时间
            //设置POST的数据域
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            if($json){
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json; charset=utf-8',
                        'Content-Length: ' . strlen($data)
                    )
                );

            }
        }
        //设置是否将响应结果存入变量，1是存入，0是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //然后将响应结果存入变量
        $output = curl_exec($curl);
        //关闭这个curl会话资源
        curl_close($curl);
        return $output;
    }
    //--------------------------------------------------------------------------------------
    /**
     * 函数的含义说明：CURL发送get请求    获取数据
     *
     * @access public
     * @param str $url 发送接口地址  https://api.douban.com/v2/movie/in_theaters?city=广州&start=0&count=10
     * @return  返回json数据
     */
    function httpGetRequest($url){

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        $output = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $output;    //返回json对象
    }

}