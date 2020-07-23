<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>修改密码</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="/Public/admin/lib/layer3/layer.js"></script>
</head>

<body>
<div class="password-list">
    <input type="text" placeholder="请输入原密码" id="oldpwd">
    <input type="text" placeholder="请输入新密码" id="newpwd">
    <input type="text" placeholder="请再次输入新密码" id="rppwd">
</div>
<a href="javascript:void(0)" class="login-out" id="btn">修改密码</a>
<div class="spring-window">
    <div class="win-box">
        <p>确定是否修改密码?</p>
            <span>
				<a href="javascript:void(0)" class="win-btn-cancle">取消</a>
				<a href="javascript:void(0)" class="win-btn-sure" onclick="upsave()">确定</a>
			</span>
    </div>
</div>
<script>
    function upsave(){
        var oldpwd = $("#oldpwd").val();
        var newpwd = $("#newpwd").val();
        var rppwd = $("#rppwd").val();
        if(oldpwd == ''){
            $(".spring-window").hide();
            layer.msg('请输入原密码');
            return false;
        }
        if(newpwd == ''){
            $(".spring-window").hide();
            layer.msg('请输入新密码');
            return false;
        }
        if(rppwd == ''){
            $(".spring-window").hide();
            layer.msg('请再次输入新密码');
            return false;
        }
        if(newpwd != rppwd){
            $(".spring-window").hide();
            layer.msg('新密码与确认密码要保持一致');
            return false;
        }
        $.post('/home/netsign/password',{oldpwd:oldpwd,newpwd:newpwd},function(data){
            if(data.code==1){
                $(".spring-window").hide();
                layer.msg('修改成功');
                setTimeout(function(){
                    window.location.href='/home/netsign/index'
                },1000)
            }else if(data.code==2){
                $(".spring-window").hide();
                setTimeout(function(){
                    window.location.href='/home/index/index'
                },1000)
            }else if(data.code==3){
                $(".spring-window").hide();
                layer.msg(data.msg);
            }
        },'json')
    }
</script>
</body>
</html>