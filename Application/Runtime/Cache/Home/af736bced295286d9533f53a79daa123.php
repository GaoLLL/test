<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
<title>注册</title>
<link type="text/css" rel="stylesheet" href="/files/css/main.css">
	<script src="https://g.alicdn.com/dingding/dingtalk-jsapi/2.7.13/dingtalk.open.js"></script>

<script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>

<script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
<script language="javascript" type="text/javascript" src="/Public/admin/lib/layer3/layer.js"></script>
</head>

<body>
	<div class="login-logo">
		<img src="/files/images/login-logo.png">
	</div>
	<ul class="login-list">
		<li>
			<p>
				<img src="/files/images/login-icon-01.png">
			</p>
			<span>账号</span>
			<input type="text" placeholder="请输入注册手机号" id="tel">
		</li>
		<li>
			<p>
				<img src="/files/images/login-icon-01.png">
			</p>
			<span><?php echo ($username); ?></span>
			<input type="text" placeholder="请输入姓名" id="username" >
		</li>
		<li class="login-ID">
			<p>
				<img src="/files/images/login-icon-03.png">
			</p>
			<span><?php echo ($idnumber); ?></span>
			<input type="text" placeholder="请输入身份证号码" id="idnumber">
		</li>
		<li>
			<p>
				<img src="/files/images/login-icon-02.png">
			</p>
			<span>密码</span>
			<input type="password" placeholder="请输入密码" id="password">
		</li>
		<li class="login-code">
			<p>
				<img src="/files/images/login-icon-04.png">
			</p>
			<span>验证码</span>
			<input type="text" placeholder="请输入验证码" id="j_verify">
			<img id="verify_img" alt="点击更换" title="点击更换" src="<?php echo U('/home/index/verify',array());?>" class="m login-safe-code">
		</li>
	</ul>
	<input type="hidden" value="<?php echo ($usertype); ?>" id="usertype"/>
	<a href="javascript:void(0);" class="login-btn">注册</a>
	<script>
        $("#verify_img").click(function() {
            var verifyURL = "/home/index/verify";
            var time = new Date().getTime();
            $("#verify_img").attr({
                "src" : verifyURL + "/" + time
            });
        });

        window.ddcode;
        dd.ready(function() {
            dd.runtime.permission.requestAuthCode({
                corpId: "ding3a990c8bd424254835c2f4657eb6378f", // 企业id
                onSuccess: function (info) {
                    ddcode = info.code // 通过该免登授权码可以获取用户身份
                    alert(code);
                },
                onFail : function(err) {
                    alert(JSON.stringify(err));
                }


            });
        });


        $('.login-btn').click(function(){
            var ddcode = '62c568ed86833e129f449899d4a9cece';
            // alert(ddcode);
			var tel = $("#tel").val();
			var username = $("#username").val();
			var idnumber = $("#idnumber").val();
			var password = $("#password").val();
			var usertype = $("#usertype").val();
			var code  = $("#j_verify").val();
			if(tel==''){
			    layer.msg('帐号不能为空');
			    return false;
			}
            if(username==''){
                layer.msg('<?php echo ($username); ?>不能为空');
                return false;
            }
            if(idnumber==''){
                layer.msg('<?php echo ($idnumber); ?>不能为空');
                return false;
            }
            if(password==''){
                layer.msg('密码不能为空');
                return false;
            }
            if(code==''){
                layer.msg('验证码不能为空');
                return false;
            }
			$.post('/home/index/reg',{tel:tel,username:username,idnumber:idnumber,password:password,usertype:usertype,code:code,ddcode:ddcode},function(data){
				if(data.code!=1){
					layer.msg(data.msg);
                    var verifyURL = "/home/index/verify";
                    var time = new Date().getTime();
                    $("#verify_img").attr({
                        "src" : verifyURL + "/" + time
                    });
					return false;
				}else{
				    layer.msg(data.msg);
				    setTimeout(function(){
				        window.location.href='/home/index/index'
					},1000)
				}
			},'json')
		})
	</script>
</body>
</html>