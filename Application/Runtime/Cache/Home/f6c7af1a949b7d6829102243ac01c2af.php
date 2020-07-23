<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>发起签署</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="/files/layer_mobile/layer.js"></script>
</head>

<body>
<div class="signing-info">
    <img src="<?php echo ($info["img"]); ?>">
    <div class="signing-cont">
        <p class="signing-cont-title"><?php echo ($worker); ?></p>
        <p class="signing-cont-tips">
            <span>发起</span>
        </p>
        <p class="signing-cont-text">
            <span>发起时间：<?php echo (date("Y-m-d H:i:s",$time)); ?> </span>
            <span>文件数量：<?php echo ($count); ?></span>
        </p>
    </div>
</div>
<div class="signing-list">
    <span>合同名称</span>
    <input type="text" class="signing-list-name" placeholder="请输入文件名称" id="signname">
</div>
<div class="signing-list">
    <span>办公人员</span>
    <input type="text" class="signing-list-date"  readonly id="endtime" onclick="add()"  placeholder="请选择大厅办公人员">
</div>
<div id="sss" style="display: none;">

    <div class="sign-seal sign-seal-cur">
        <div class="tax-documents-search2" style="top:0;">
            <input type="text" placeholder="请输入搜索文件名称" id="signnamesearch">
            <a href="javascript:void(0)" onclick="getsignlist(this)"   class="tax-documents-search-btn">搜索</a>
        </div>
        <!--<div class="search-height4"></div>--><!-- 显示 -->
        <div class="search-height2" style="height: 11.93vw"></div><!-- 不显示-->
        <ul class="institutional-man" id="signnamelist-user" style="overflow: scroll; height: 40vh;">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li onclick="chosepeople(this)" data-uid="<?php echo ($vo["id"]); ?>" data-username="<?php echo ($vo["username"]); ?>">
                    <a class="background-none">
                        <p class="signature-choose"></p>
                        <img src="<?php echo ($vo["topimage"]); ?>">
                        <div class="institutional-man-info2">
                            <p><?php echo ($vo["username"]); ?></p>
                            <span><?php echo ($vo["tel"]); ?></span>
                        </div>
                    </a>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>

    <script type="text/javascript">
        function add() {
            var pageii = layer.open({
                type: 1,
                content: $('#sss').html(),
                anim: 'up',
                style: 'position:fixed; left:0; top:0; width:100%; height:100%; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
            });
        }
        function getsignlist(obj) {

            var title =  $(".layui-m-layer").find("#signnamesearch").val();
            var html = '';
            $.post('/home/sendsign/getzzjg', {title:title}, function (data) {
                $.each(data.data, function (index, item) {

                    html += '<li onclick="chosepeople(this)" data-uid="'+item.id+'" data-username="'+item.username+'">' +
                        '                    <a class="background-none">' +
                        '                        <p class="signature-choose"></p>' +
                        '                        <img src="'+item.topimage+'">' +
                        '                        <div class="institutional-man-info2">' +
                        '                            <p>'+item.username+'</p>' +
                        '                            <span>'+item.tel+'</span>' +
                        '                        </div>' +
                        '                    </a>' +
                        '                </li>';


                });
                $(".layui-m-layer").find("#signnamelist").html('');
                $(".layui-m-layer").find("#signnamelist").css('height',0);
                $(".layui-m-layer").find("#signnamelist-user").html('');
                $(".layui-m-layer").find("#signnamelist-user").append(html);
            }, 'json')

        }

        function tjpeople(){
            var username = $("#nowusername").val();
            $("#endtime").val(username);
            layer.closeAll();

        }
        function chosepeople(obj){
            var username = $(obj).data('username');
            var tel   = $(obj).data('tel');
            var type  = $(obj).data('type');
            var uid = $(obj).data('uid');
            $("#nowusername").val(username);
            $("#nowuid").val(uid);
            $("#nowtel").val(tel);
            $("#nowtype").val(type);
            $(obj).find('p').addClass('signature-choose-cur');
            $(obj).siblings().find('p').removeClass("signature-choose-cur");
        }

        function zztj() {

            var title = $("#signname").val();
            var receiveuid = $("#nowuid").val();
            var img = $("#img").val();

                if (img == '') {
                    $(".spring-window").hide();
                    layer.open({
                        content: '请先选择要签署的文件图片'
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });

                    return false;
                }
                if (title == '') {
                    $(".spring-window").hide();
                    layer.open({
                        content: '请输入文件名称'
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
                    return false;
                }
                if (receiveuid == '') {
                    $(".spring-window").hide();
                    layer.open({
                        content: '请先选择签办公人员'
                        , skin: 'msg'
                        , time: 2 //2秒后自动关闭
                    });
                    return false;
                }
                $.post('/home/sendsign/zrrtj', {
                    title: title,
                    receiveuid: receiveuid,
                    img: img
                }, function (data) {
                    if (data.code == 1) {
                        $(".spring-window").hide();
                        layer.open({
                            content: '成功'
                            , skin: 'msg'
                            , time: 2 //2秒后自动关闭
                        });
                        setTimeout(function () {
                            window.location.href = '/home/netsign/index';
                        }, 1000)
                    } else {
                        $(".spring-window").hide();
                        layer.open({
                            content: data.msg
                            , skin: 'msg'
                            , time: 2 //2秒后自动关闭
                        });
                        return false;
                    }
                }, 'json')

        }

     </script>
    <div class="sure-btn-height"></div>
    <div class="tax-documents-down-btn">
        <a href="javascript:void(0);" onclick="tjpeople(this)" data-upid="0" id="tjpeople">确定</a>
    </div>
</div>
<input type="hidden" value="<?php echo ($minfo["id"]); ?>" id="mid"/>
<input type="hidden" value="<?php echo ($id); ?>" id="img"/>
<input type="hidden" value="" id="choseseal"/>
<input type="hidden" value="" id="chosesealname"/><!-- 印章名称 -->
<input type="hidden" value="" id="nowusername"/><!-- 姓名 -->
<input type="hidden" value="" id="nowtype"/><!-- 签名/印章 -->
<input type="hidden" value="" id="nowtel"/><!-- 电话 -->
<input type="hidden" value="" id="nowuid"/><!-- 当前添加用户id -->
<input type="hidden" value="1" id="nowposttype"/><!-- 是否为自动流转 -->

<div class="sure-btn-height"></div>
<div class="tax-documents-down-btn">
    <a href="JavaScript:void(0)" class="sign-initiate-sure">确定</a>
</div>
<div class="spring-window">
    <div class="win-box">
        <p>确定是否发起签署?</p>
        <span>
            <a href="javascript:void(0)" class="win-btn-cancle">取消</a>
            <a href="javascript:void(0)" class="win-btn-sure" onclick="zztj()">确定</a>
        </span>
    </div>
</div>
</body>
</html>