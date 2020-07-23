<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/19
 * Time: 14:57
 */
namespace Api\Controller;
use Think\Controller;
class BaseController extends Controller
{
    public function index()
    {

    }

    public function getToken()
    {
        $str = 'wqxt' . time();
        return md5($str);
    }

    public function getdbToken($userid, $token)
    {
        if ($userid == '') {
            return 3;
        } else {
            $res = M('customer')->where("id='$userid'")->find();
            if ($token == $res['token']) {
                return 1;
            } else {
                return 2;
            }
        }

    }

    public function time_tran($the_time)
    {
        $now_time = date("Y-m-d H:i:s", time() + 8 * 60 * 60);
        $now_time = strtotime($now_time);
        $show_time = strtotime($the_time);
        $shows_time = date("m-d",$show_time);
        $dur = $now_time - $show_time;
        $now = date("H:i", strtotime($the_time));
        if ($dur < 0) {
            return $shows_time;
        } else {
            if ($dur < 60) {
                return $now;
            } else {
                if ($dur < 3600) {
                    return $now;
                } else {
                    if ($dur < 86400) {
                        return $now;
                    } else {
                        if ($dur < 259200) {//3天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return $shows_time;
                        }
                    }
                }
            }
        }
    }
}