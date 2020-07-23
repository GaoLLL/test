<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>网签</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body>
<div class="start-signing">
    <div class="start-signing-num">
        <!--<a href="<?php echo U('sendsign/waitmysignlist');?>">
            <p>待我签署</p>
            <span><?php echo ($mycount); ?></span>
        </a>
        <a href="<?php echo U('sendsign/mysignlist');?>">
            <p>待他人签署</p>
            <span><?php echo ($othercount); ?></span>
        </a>-->
    </div>
    <a href="<?php echo U('Sendsign/photo');?>" class="start-signing-btn">开始签署</a>
</div>

<div class="net-sign-footer-height"></div>
<ul class="net-sign-footer">
    <li>
        <a href="<?php echo U('Sendsign/index');?>" class="net-sign-footer-cur">
            <img src="/files/images/net-sign-footer-icon-01-a.png">
            <span>发起审批</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Sendsign/alllist');?>">
            <img src="/files/images/net-sign-footer-icon-03.png">
            <span>签署文件</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Sendsign/userlist');?>">
            <img src="/files/images/net-sign-footer-icon-04.png">
            <span>机构设置</span>
        </a>
    </li>
</ul>
</body>
</html>