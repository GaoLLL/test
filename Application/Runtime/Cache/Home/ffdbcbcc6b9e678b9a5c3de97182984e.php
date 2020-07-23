<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>拒签理由</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="/files/layer_mobile/layer.js"></script>
</head>
<input type="hidden" id="id" value="<?php echo ($id); ?>"/>
<input type="hidden" id="orders" value="<?php echo ($orders); ?>"/>

<body>
<textarea placeholder="请输入拒签理由" class="reason-refusal" id="content"></textarea>
<a href="javascript:void(0);" class="login-out" onclick="jq()">确定</a>
<script>
    function jq(){
        var id = $("#id").val();
        var orders = $("#orders").val();
        var content = $("#content").val();
        $.post('/home/sendsign/jqdo',{id:id,orders:orders,content:content},function(data){
            if(data.code==1){
                $(".spring-window").hide();
                layer.open({
                    content: '拒签成功'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                setTimeout(function(){
                    window.location.href='/home/sendsign/forminfo/id/'+id;
                },1000)
            }else{
                $(".spring-window").hide();
                layer.open({
                    content: data.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                return false;
            }
        },'json')
    }
</script>
</body>
</html>