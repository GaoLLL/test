<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
<title>登录</title>
<link type="text/css" rel="stylesheet" href="/files/css/main.css">
<script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
<script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
	<script src="https://g.alicdn.com/dingding/dingtalk-jsapi/2.7.13/dingtalk.open.js"></script>
<script language="javascript" type="text/javascript" src="/Public/admin/lib/layer3/layer.js"></script>
</head>

<body>
	<div class="login-logo">
		<img src="/files/images/login-logo.png">
	</div>
	<div class="login-choose">
		<!--<a href="javascript:void(0)" class="login-choose-cur">税务机关</a>-->
		<a href="javascript:void(0)" <?php if(($usertypes) == "0"): ?>class="login-choose-cur"<?php endif; ?> >纳税人</a>
		<a href="javascript:void(0)" <?php if(($usertypes) == "1"): ?>class="login-choose-cur"<?php endif; ?> >自然人</a>
		<input type="hidden" class="login-number" value="<?php echo ($usertypes); ?>">
	</div>
	<ul class="login-list">
		<li>
			<p>
				<img src="/files/images/login-icon-01.png">
			</p>
			<span>账号</span>
			<input type="text" placeholder="请输入注册手机号" id="username">
		</li>
		<li>
			<p>
				<img src="/files/images/login-icon-02.png">
			</p>
			<span>密码</span>
			<input type="password" placeholder="请输入密码" id="password">
		</li>
	</ul>
	<a href="javascript:void(0)" class="login-btn">登录</a>
	<a href="javascript:void(0)" class="registered-btn">注册</a>
	<script>

        window.ddcode;
        dd.ready(function() {
            dd.runtime.permission.requestAuthCode({
                corpId: "<?php echo C('corpId');?>", // 企业id
                onSuccess: function (info) {
                    ddcode = info.code // 通过该免登授权码可以获取用户身份
                    alert(code);
                },
                onFail : function(err) {
                    alert(JSON.stringify(err));
                }


            });
        });


		$(".registered-btn").click(function(){
		    var usertype = $(".login-number").val();
		    window.location.href="/home/index/reg/usertype/"+usertype;
		})
        $(".login-btn").click(function(){
            var username = $("#username").val();
            var password = $("#password").val();
            if(username==''){
                layer.msg('帐号不能为空');
                return false;
			}
            if(password==''){
                layer.msg('密码不能为空');
                return false;
            }
            $.post('/home/index/login',{tel:username,password:password},function(data){
                layer.msg(data.msg);
                if(data.code == 1){
                    setTimeout(function(){
                        window.location.href='/home/netsign/index';
					},1000)
				}
			},'json')
        })
	</script>
</body>
</html>