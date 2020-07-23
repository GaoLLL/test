<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */
namespace Api\Controller;
use Think\Controller;
class DingController extends Controller
{
	private $AppKey = '';
    private $AppSecret = '';
    private $AgentId = '';

    public function __construct()
    {
        $this->AppKey = C('AppKey');
        $this->AppSecret = C('AppSecret');
        $this->AgentId = C('AgentId');
    }
    public function index()
    {
    	
    }
	//获取access_token
    public function getToken()
    {

        $token_url='https://oapi.dingtalk.com/gettoken?appkey='.$this->AppKey.'&appsecret='.$this->AppSecret;
 
		$token_data=$this->httpGetRequest($token_url);

		$token_arr=json_decode($token_data,true);
		$token=$token_arr['access_token'];

		return $token;
    }
	//根据unionid获取userid
    public function getUseridByUnionid($unionid)
    {
		$token = $this->getToken();
		//https://oapi.dingtalk.com/gettoken?appkey=dingq2skn4pzngwiwu3r&appsecret=-MHJBkYmzCBUfBwa2PlEGUQ9WnvftRdJQof5uuju-3xMUdR1yLwCNU5Ej2qFXOa5
        $url="https://oapi.dingtalk.com/user/getUseridByUnionid?access_token=".$token."&unionid=".$unionid;

		$data=httpGetRequest($url);
		$arr=json_decode($data,true);
		
		return $arr;

    }
	//根据code获取userid
    public function getuserinfo()
    {

        $token = $this->getToken();
        $code = $_REQUEST['code'];
		//$code="b35c332477bc3873b7950dffe00092f2";
        $url="https://oapi.dingtalk.com/user/getuserinfo?access_token=".$token."&code=".$code;
		$data=$this->httpGetRequest($url);
		$arr=json_decode($data,true);
		return $arr;

    }
	//获取部门id
	//父部门id。根部门的话传1
    public function getdepartmentId($id)
    {
		$token = $this->getToken();
        $sub_url="https://oapi.dingtalk.com/department/list_ids?access_token=".$token."&id=".$id;

		$sub_data=httpGetRequest($sub_url);
		$sub_arr=json_decode($sub_data,true);
		
		return $sub_arr;

    }
	//获取部门列表
	//父部门id（如果不传，默认部门为根部门，根部门ID为1）
    public function getdepartmentList()
    {
		$token = $this->getToken();
        $department_url="https://oapi.dingtalk.com/department/list?access_token=".$token."&id=1";
		$department_data=httpGetRequest($department_url);
		$department_arr=json_decode($department_data,true);
		
		return $department_arr;

    }
	//获取部门用户userid列表
	//deptId:部门id
    public function getuserId($deptid)
    {
		$token = $this->getToken();
        $userid_url="https://oapi.dingtalk.com/user/getDeptMember?access_token=".$token."&deptId=".$deptid;
		$userid_data=httpGetRequest($userid_url);
		$userid_arr=json_decode($userid_data,true);
		
		return $userid_arr;

    }	
	//获取部门用户
	//deptid:部门id，offset:第几页，size：每页数量
    public function getuserName($deptid,$offset,$size)
    {
		$token = $this->getToken();
        $username_url="https://oapi.dingtalk.com/user/simplelist?access_token=".$token."&department_id=".$deptid."&offset=".$offset."&size=".$size;
		$username_data=httpGetRequest($username_url);
		$username_arr=json_decode($username_data,true);
		
		return $username_arr;
    }
	//获取用户详情
	//userid:用户id
    public function getuser($user_id)
    {
		$token = $this->getToken();
        $user_url="https://oapi.dingtalk.com/user/get?access_token=".$token."&user_id=".$user_id;
		$user_data=httpGetRequest($user_url);
		$user_arr=json_decode($user_data,true);
		
		return $user_arr;
    }
	//上传媒体文件
	//type:媒体文件类型，分别有图片（image）、语音（voice）、普通文件(file)
	//media:form-data中媒体文件标识，有filename、filelength、content-type等信息
    public function getupload($fileName,$fileType,$fileSize)
    {
		$token = $this->getToken();
        $upload_url="https://oapi.dingtalk.com/media/upload?access_token=".$token."&type=image";
		$upload_data=array();
		$upload_data['filename']=$fileName;
		$upload_data['content-type']=$fileType;
		$upload_data['filelength']=$fileSize;
		$upload_data=httpPostRequest($upload_url,$upload_data);
		$upload_arr=json_decode($upload_data,true);
		
		return $upload_arr;
    }
    //发送工作通知消息（文本
	//userid_list:接收者的用户userid列表，最大列表长度：100

	//to_all_user：是否发送给企业全部用户（否传false）
	//msg：消息内容（json对象），文本消息例：{"msgtype":"text","text":{"content":"消息内容"}}
    public function sendMes($msg,$userid_list,$to_all_user)
    {
		$token = $this->getToken();
        $mes_url="https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2?access_token=$token";
		$mes_data=array();
		$mes_data['agent_id']=$this->AgentId;
		$mes_data['userid_list']=$userid_list;
		$mes_data['to_all_user']=$to_all_user;
		$mes_data['msg']="{'msgtype':'text','text':{'content':$msg}}";
		$mes=httpPostRequest($mes_url, $mes_data);
		$mes_arr=json_decode($mes,true);

		
		return $mes_arr;
    }
    //发送工作通知消息（图片
	//userid_list:接收者的用户userid列表，最大列表长度：100

	//to_all_user：是否发送给企业全部用户（否传false）
	//msg：消息内容（json对象），文本消息例：{"msgtype":"text","text":{"content":"消息内容"}}
    public function sendMes3($msg,$userid_list,$to_all_user)
    {
		$token = $this->getToken();
        $mes_url="https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2?access_token=$token";
		$mes_data=array();
		$mes_data['agent_id']=$this->AgentId;
		$mes_data['userid_list']=$userid_list;
		$mes_data['to_all_user']=$to_all_user;
		$mes_data['msg']="{'msgtype':'image','image':{'media_id':$msg}}";
		$mes=httpPostRequest($mes_url, $mes_data);
		$mes_arr=json_decode($mes,true);

		
		return $mes_arr;
    }	
	//发送工作通知消息（链接
	//userid_list:接收者的用户userid列表，最大列表长度：100

	//to_all_user：是否发送给企业全部用户（否传false）
	//msg：消息内容（json对象），文本消息例：{"msgtype":"text","text":{"content":"消息内容"}}
    public function sendMes2($msgurl,$picUrl,$title,$text,$userid_list,$to_all_user)
    {
		$token = $this->getToken();
        $mes_url="https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2?access_token=$token";
		$mes_data=array();
		$mes_data['agent_id']=$this->AgentId;
		$mes_data['userid_list']=$userid_list;
		$mes_data['to_all_user']=$to_all_user;
		$mes_data['msg']="{'msgtype':'link','link':{'messageUrl': $msgurl,'picUrl':$picUrl,'title': $title,'text': $text}}";
		$mes=httpPostRequest($mes_url, $mes_data);
		$mes_arr=json_decode($mes,true);

		
		return $mes_arr;
    }
	//查询工作通知消息的发送进度
	//task_id：发送消息时钉钉返回的任务id
    public function getsendprogress($task_id)
    {
		$token = $this->getToken();
        $url="https://oapi.dingtalk.com/topapi/message/corpconversation/getsendprogress?access_token==$token";
		$data=array();
		$data['agent_id']=$this->AgentId;
		$data['task_id']=$task_id;
		$mes=httpPostRequest($url, $data);
		$mes_arr=json_decode($mes,true);

		
		return $mes_arr;
    }
	//查询工作通知消息的发送结果
	//task_id：发送消息时钉钉返回的任务id
    public function getsendresult($task_id)
    {
		$token = $this->getToken();
        $url="https://oapi.dingtalk.com/topapi/message/corpconversation/getsendresult?access_token==$token";
		$data=array();
		$data['agent_id']=$this->AgentId;
		$data['task_id']=$task_id;
		$mes=httpPostRequest($url, $data);
		$mes_arr=json_decode($mes,true);

		
		return $mes_arr;
    }

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
}