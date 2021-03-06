<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>添加签名</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="/files/layer_mobile/layer.js"></script>
    <script type="text/javascript" src="/files/js/esign.js"></script>
</head>

<body style="background-color: #EEEEEE;">
<div class="canvasDiv">
    <div id="editing_area">
        <canvas id="canvasEdit"></canvas>
    </div>
    <div class="btnDiv">
        <a id="sign_clear" class="clearBtn">
            <img src="/files/images/sign_reset.png">
        </a>
        <a id="sign_ok" class="okBtn">
            <img src="/files/images/sign_sure.png">
        </a>
    </div>
</div>
<div class="imgDiv">
    <span id="sign_show"></span>
</div>

<script type="text/javascript">
    $(function(){
        //初始化动作，根据DOM的ID不同进行自定义，如果不写则内部默认取这四个
        $().esign("canvasEdit", "sign_show", "sign_clear", "sign_ok");
    });

</script>
</body>
</html>