<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>我的签名</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="/files/layer_mobile/layer.js"></script>
</head>

<body style="background-color: #EEEEEE;">
<div class="no-signature">
    <img src="/files/images/no_signature.png">
    <p>您还没有签名</p>
</div>
<?php if(($info) == ""): ?><a href="<?php echo U('Netsign/mysignnameadd');?>" class="sign-my">
        <img src="/files/images/sign_add.png">
    </a><?php endif; ?>

<ul class="signature-list signature-cur">
    <?php if(($info) != ""): ?><li>
            <img src="<?php echo ($info); ?>" class="sign-img">
            <p>
                <a href="<?php echo U('Netsign/mysignnameadd');?>" class="signature-edit">
                    <img src="/files/images/signature_edit.png">
                    <span>编辑</span>
                </a>
                <a href="javascript:void(0)" class="signature-delete">
                    <img src="/files/images/signature_delete.png">
                    <span>删除</span>
                </a>
            </p>
        </li><?php endif; ?>
</ul>
<script>
    $('.signature-delete').click(function(){
        layer.open({
            content: '确定删除？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post('/home/netsign/delmysignname','',function(data){
                    if(data.code==1){
                        layer.open({
                            content: data.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        setTimeout(function(){
                            location.reload();
                        },1000)
                    }else if(data.code==2){
                        window.location.href='/home/index/index'
                    }else{
                        layer.open({
                            content: data.msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                },'json')
                //location.reload();

            }
        });
    })
</script>
</body>
</html>