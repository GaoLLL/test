<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>公告详情</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body>
<div class="notice-detail-title"><?php echo ($info["title"]); ?></div>
<div class="notice-detail-date"><?php echo (date('Y-m-d H:i:s',$info["addtime"])); ?></div>
<div class="notice-detail-cont"><?php echo ($info["content"]); ?></div>
</body>
</html>