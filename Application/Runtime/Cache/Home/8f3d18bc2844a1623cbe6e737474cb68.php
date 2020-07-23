<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>人员详情</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body>
<div class="institutional-man-cont">
    <img src="<?php echo ($info["topimage"]); ?>">
    <p>
        <span><?php echo ($info["username"]); ?></span>
        <m><?php echo ($info["framework"]); ?></m>
        <m><?php echo ($info["tel"]); ?></m>
    </p>
</div>
<ul class="institutional-man-cont-list">
    <li>
        <p>企业/组织</p>
        <span><?php echo ($info["framework"]); ?></span>
    </li>
    <li>
        <p>电话</p>
        <span><?php echo ($info["tel"]); ?></span>
    </li>
    <li>
        <p>办公电话</p>
        <span><?php echo ($info["phone"]); ?></span>
    </li>
    <li>
        <p>小号</p>
        <span><?php echo ($info["small_tel"]); ?></span>
    </li>
    <li>
        <p>部门</p>
        <span><?php echo ($info["bm"]); ?></span>
    </li>
    <li>
        <p>职位</p>
        <span><?php echo ($info["worker"]); ?></span>
    </li>
    <li>
        <p>审批权限</p>
        <span><?php echo ($info["have"]); ?></span>
    </li>
</ul>
</body>
</html>