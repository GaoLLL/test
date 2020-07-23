<?php
function subtext($text, $length)
{
    if(mb_strlen($text, 'utf8') > $length)
        return mb_substr($text, 0, $length, 'utf8').'...';
    return $text;
}
/**
 * @param int $status 1 success other error
 * @param $code 错误代码代表的值
 */
function ReturnJson($status = 1, $code)
{
    header('Content-type: application/json'); //json
    $msg = M('error')->where(array('error_id' => $code))->find();
    !$msg && $msg['error_detail'] = $code;
    exit(json_encode(array("status" => $status, "msg" => $msg['error_detail'])));
}

//输出错误信息值
function errorCode($code)
{
    $msg = M('error')->where(array(
        'error_id' => $code
    ))->find();
    !$msg && $msg['error_detail'] == $code;
    return $msg['error_detail'];

}

//生成导航菜单
function MenuformatTree($array, $pid = 0, $bs = 'belongid')
{
    if (is_array($array)) {
        $arr = array();
        $tem = array();
        foreach ($array as $v) {
            if ($v['level'] != 0) {
                if ($v[$bs] == $pid) {
                    $tem = MenuformatTree($array, $v['id']);
                    //判断是否存在子数组
                    $tem && $v['items'] = $tem;
                    $arr[$v['id']] = $v;
                }
            }
        }

        return $arr;
    }
}

//获取用户列表yby_user 以userid=>username形式返回 add by zq
function GetUserName()
{
    $usre_list = D('users')->field('id,name')->select();
    $users = array();
    foreach ($usre_list as $key => $val) {
        $users[$val['id']] = $val['name'];
    }
    return $users;
}

/**
 * @param $list
 * @param $nowPage
 * @param $listNums
 * 数组分页
 * @return array;
 */

function pageList($list, $nowPage, $listNums = 10)
{
    $count = count($list);//总数
    $toPages = ceil($count / $listNums);//总页数
    $pageList = array_slice($list, ($nowPage - 1) * $listNums, 10);
    if(empty($pageList)){
        return array('msg' => '没有更多数据');
    } else{
        return array('count' => $count, 'total' => $toPages, 'list' => $pageList, 'nowPage' => $nowPage);
    }



}

/**
 * 相差天数
 * @param $a
 * @param $b
 * @return float
 */
function count_days($a, $b)
{
    $d1 = strtotime($a);
    $d2 = strtotime($b);
    return $Days = round(($d1 - $d2) / 3600 / 24);
}
///无线递归/
function formatTree($array, $pid = 0, $bs = 'parent_id')
{
    if (is_array($array)) {
        $arr = array();
        $tem = array();
        foreach ($array as $v) {
            if ($v[$bs] == $pid) {
                $tem = formatTree($array, $v['id']);
                //判断是否存在子数组
                $tem && $v['items'] = $tem;
                $arr[] = $v;
            }
        }

        return $arr;
    }
}

function getAnswerCategory($length = 3){
	$list = D('answer_category')->select();
	foreach ($list as $k=>$v){
		$list[$k]["top"] = str_pad("",substr_count($v["route"],",")*$length,"-");
	}
	return $list;
}

function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root=0) {
	$tree = array();// 创建Tree
	if(is_array($list)) {
		// 创建基于主键的数组引用
		$refer = array();
		foreach ($list as $key => $data) {
			$refer[$data[$pk]] =& $list[$key];
		}

		foreach ($list as $key => $data) {
			// 判断是否存在parent
			$parentId = $data[$pid];
			if ($root == $parentId) {
				$tree[$data[$pk]] =& $list[$key];
			}else{
				if (isset($refer[$parentId])) {
					$parent =& $refer[$parentId];
					$parent[$child][] =& $list[$key];
				}
			}
		}
	}
	return $tree;
}

/**
 * 多维数组转化为一维数组
 * @param array $array 多维数组
 * @return array $result_array 一维数组
 */
function array_multi2single($array)
{
	//首先定义一个静态数组常量用来保存结果
	static $result_array = array();
	static $k = 0;
	//对多维数组进行循环
	foreach ($array as $key=>$value) {
		//判断是否是数组，如果是递归调用方法
		if(array_key_exists("child", $value)){
			$result_array[$k] = $value;
			unset($result_array[$k]["child"]);
			$k++;
			array_multi2single($value["child"]);
		}else{
			//如果不是，将结果放入静态数组常量
			$result_array[$k] = $value;
			$k++;
		}
	}
	//返回结果（静态数组常量）
	return $result_array;
}

/**
 * 对象转换成数组
 * @param $obj
 */
function objToArray($obj)
{
    return json_decode(json_encode($obj), true);
}
/**
 * 整理出tree数据 ---  layui tree
 * @param $pInfo
 * @param $spread
 */
function getTree($pInfo, $spread = true)
{

    $res = array();
    $tree = array();
    //整理数组
    foreach($pInfo as $key=>$vo){

        if($spread){
            $vo['spread'] = true;  //默认展开
        }
        $res[$vo['id']] = $vo;
        $res[$vo['id']]['children'] = array();
    }
    unset($pInfo);

    //查找子孙

    foreach($res as $key=>$vo){
        if(0 != $vo['pid']){
            $res[$vo['pid']]['children'][] = &$res[$key];
            $res[$vo['pid']]['nums'] = $res[$vo['pid']]['nums']+1;


        }
    }

    //过滤杂质
    foreach( $res as $key=>$vo ){
        if(0 == $vo['pid']){
            $tree[] = $vo;
        }
    }
    unset( $res );

    return $tree;
}

//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request($url,$post='',$cookie='', $returnCookie=0){
   /* if($post=''){
        httpGetRequest($url);
    }else{
        httpPostRequest($url,$post);
    }*/


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    if($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    if($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if($returnCookie){
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie']  = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    }else{
        return $data;
    }


}

function getindexuser(){

    return $_SESSION['index_userinfo'];
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

    function comFunction(){
        return "comFunction";
    }
