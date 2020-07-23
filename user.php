<?php
require 'func.php';

//获取access_token
 
$token_url='https://oapi.dingtalk.com/gettoken?appkey=dingq2skn4pzngwiwu3r&appsecret=-MHJBkYmzCBUfBwa2PlEGUQ9WnvftRdJQof5uuju-3xMUdR1yLwCNU5Ej2qFXOa5';
 
$token_data=httpGetRequest($token_url);
$token_arr=json_decode($token_data,true);
$access_token=$token_arr['access_token'];

//获取部门id
$code="9117829fc2ba36ffa6c39f4a1c2bfe13";
$sub_url="https://oapi.dingtalk.com/user/getuserinfo?access_token=".$access_token."&code=".$code;

$sub_data=httpGetRequest($sub_url);
$sub_arr=json_decode($sub_data,true);

var_dump($sub_arr);

?>