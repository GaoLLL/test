<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>个人中心</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body>
<div class="personal-info">
    <img src="<?php echo ($info["topimage"]); ?>">
    <p><?php echo ($info["username"]); ?></p>
    <span><?php echo ($info["tel"]); ?></span>
</div>
<ul class="personal-list">
    <li>
        <a href="<?php echo U('Netsign/wsinfo');?>">
            <img src="/files/images/personal_icon_01.png">
            <span>我的签名</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Netsign/seal');?>">
            <img src="/files/images/personal_icon_02.png">
            <span>我的印章</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Netsign/password');?>">
            <img src="/files/images/personal_icon_03.png">
            <span>修改密码</span>
        </a>
    </li>
</ul>
<a href="javascript:void(0)" class="login-out">退出登录</a>
<div class="spring-window">
    <div class="win-box">
        <p>确定是否退出登录</p>
        <span>
				<a href="javascript:void(0)" class="win-btn-cancle">取消</a>
				<a href="javascript:void(0)" class="win-btn-sure" onclick="loginout()">确定</a>
			</span>
    </div>
</div>
<script>
    function loginout(){
        $.post('/home/netsign/loginout','',function(){
            window.location.href = '/home/index/index';
        },'json')
    }
</script>
<div class="index-footer-height"></div>
<ul class="index-footer">
    <li>
        <a href="<?php echo U('Netsign/index');?>">
            <img src="/files/images/index-footer-icon-01.png">
            <span>首页</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Netsign/myself');?>" class="index-footer-cur">
            <img src="/files/images/index-footer-icon-02-a.png">
            <span>个人中心</span>
        </a>
    </li>
</ul>
</body>
</html>