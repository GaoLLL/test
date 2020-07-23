<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>立即签字</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <link type="text/css" rel="stylesheet" href="/files/swiper/css/swiper.min.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/touch.js"></script>
    <script language="javascript" type="text/javascript" src="/files/swiper/js/swiper.min.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/pinchzoom.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/draggabilly.pkgd.min.js"></script>
    <script language="javascript" type="text/javascript" src="/files/layer_mobile/layer.js"></script>
</head>

<body id="canvasdemo">
<div class="page">
    <div class="swiper-container sign-now">
        <div class="swiper-wrapper sign-now-cont swiper-no-swiping" id="list">
            <?php if(is_array($imglist)): $i = 0; $__LIST__ = $imglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                    <div class="pinch-zoom pinch-zoom-cur">
                        <img src="<?php echo ($vo["img"]); ?>" class="pinch-img"/>
                    </div>
                    <!--<img src="" class="sign-ccanvas-img" style="display: none;">-->
                </div><?php endforeach; endif; else: echo "" ;endif; ?>

            <!--<div class="swiper-slide">
                <div class="pinch-zoom pinch-zoom-cur">
                    <img src="/files/images/sign_document_preview_img.png" class="pinch-img"/>
                </div>
                <img src="" class="sign-ccanvas-img">
            </div>
            <div class="swiper-slide">
                <div class="pinch-zoom pinch-zoom-cur">
                    <img src="/files/images/sign_document_preview_img.png" class="pinch-img"/>
                </div>
                <img src="" class="sign-ccanvas-img">
            </div>-->
        </div>
        <div class="sell_control">
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
        </div>
        <a href="javascript:void(0)" class="sign-refuse">拒签</a>
        <a href="javascript:void(0)" class="sign-accept">签署</a>
    </div>
    <script>
        //			$(function () {
        //            	$('div.pinch-zoom-cur').each(function () {
        //					new RTP.PinchZoom($(this), {});
        //				});
        //			})
        var swiper = new Swiper('.swiper-container', {
            pagination: {
                el: '.swiper-pagination',
                type: 'fraction',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
</div>
<input type="hidden" id="signid" value="<?php echo ($id); ?>"/>
<input type="hidden" id="orders" value="<?php echo ($orders); ?>"/>
<div class="immediately-height"></div>
<div class="immediately-cont">
    <a href="javascript:void(0)" class="immediately-img">
        <p>
            <img src="<?php echo ($signbtn); ?>">
        </p>
    </a>
    <a href="javascript:void(0)" class="immediately-img2">
        <p>
            <img src="<?php echo ($timeimg); ?>">
        </p>
    </a>
</div>
<div class="spring-window win-refuse">
    <div class="win-box">
        <p>确定拒绝签署？</p>
        <span>
				<a href="javascript:void(0)" class="win-btn-cancle">取消</a>
				<a href="/home/sendsign/jq/id/<?php echo ($id); ?>/orders/<?php echo ($orders); ?>" class="win-btn-sure">确定</a>
			</span>
    </div>
</div>
<div class="spring-window win-accept">
    <div class="win-box">
        <p>确定立即签署？</p>
        <span>
				<a href="javascript:void(0)" class="win-btn-cancle">取消</a>
				<a href="javascript:void(0)" class="win-btn-sure" onclick="tjs()">确定</a>
			</span>
    </div>
</div>
<script>
    function tjs(){
        var arr = new Array();
        var biao = false;
        $(".swiper-slide").each(function(i){
            var ss = $(".swiper-slide").eq(i).find(".sign-now-img").length;
            var tt = $(".swiper-slide").eq(i).find(".date-img").length;
            var winWidth = document.documentElement.offsetWidth;
            var id = "signCSS"+i;//签名外框
            var id2 = "imgCSS"+i;//签名图片
            var id3 = "dateCSS"+i;//日期外框
            var id4 = "dateImgCSS"+i;//日期图片
            //arr[i] = new Array();

            if(ss > 0){
                var seal = document.getElementById(id);
                var seal2 = document.getElementById(id2);
                var src2Width = seal2.offsetWidth;//签名图片宽度
                var src2Height = seal2.offsetHeight;//签名图片高度
                var src2Left = seal.offsetLeft+0.0688*winWidth;//签名图片X轴距离
                console.log(seal.offsetLeft);
                console.log(src2Left);
                var src2Top = seal.offsetTop+0.0688*winWidth;//签名图片Y轴距离
                biao = true;
               }else{
                var src2Width = '-1';//签名图片宽度
                var src2Height = '-1';//签名图片高度
                var src2Left = '-1';//签名图片X轴距离
                var src2Top = '-1';//签名图片Y轴距离
                //arr[i].push({sign_left:src2Left,sign_top:src2Top,sign_width:src2Width,sign_height:src2Height})
            }

            if(tt>0){
                var seal3 = document.getElementById(id3);
                var seal4 = document.getElementById(id4);
                var src4Width = seal4.offsetWidth;//日期图片宽度
                var src4Height = seal4.offsetHeight;//日期图片高度
                var src4Left = seal3.offsetLeft+0.0688*winWidth;//日期图片X轴距离
                var src4Top = seal3.offsetTop+0.0688*winWidth;//日期图片Y轴距离
                biao = true;
               // arr[i].push({date_left:src2Left,date_top:src2Top,date_width:src2Width,date_height:src2Height})
            }else{
                var src4Width = '-1';//日期图片宽度
                var src4Height = '-1';//日期图片高度
                var src4Left = '-1';//日期图片X轴距离
                var src4Top = '-1';//日期图片Y轴距离
            }

            arr.push({sign_left:src2Left,sign_top:src2Top,sign_width:src2Width,sign_height:src2Height,date_left:src4Left,date_top:src4Top,date_width:src4Width,date_height:src4Height})

        })
        // console.log(arr);

        var winWidth = document.documentElement.offsetWidth * 0.8594;
        var winheight = document.documentElement.offsetWidth * 1.2031;
        // console.log(winWidth);
        // console.log(winheight);
        if(biao){
            var id = $("#signid").val();
            var orders = $("#orders").val();
            $.post('/home/sendsign/sendsigndo',{id:id,orders:orders,arr:arr,pw:winWidth,ph:winheight},function(data){
                // alert(data.code);return false;
                $(".spring-window").hide();
                layer.open({
                    content: data.msg
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                setTimeout(function(){
                    window.location.href='/home/sendsign/forminfo/id/'+id;
                },1000)
            },'json')
        }else{
            $(".spring-window").hide();
            layer.open({
                content: '请先签名'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }

    }

</script>
<script>
    function tj(){
        var id = $("#signid").val();
        var orders = $("#orders").val();
        var arr = new Array();
        $("#list").find(".swiper-slide").each(function(){
            var nowurl = $(this).find(".sign-ccanvas-img").attr('src');
            arr.push(nowurl)
        })
        $.post('/home/sendsign/sendsigndo',{id:id,orders:orders,arr:arr},function(data){
            $(".spring-window").hide();
            layer.open({
                content: data.msg
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            setTimeout(function(){
                window.location.href='/home/sendsign/forminfo/id/'+id;
            },1000)
        },'json')
        console.log(arr);
    }
</script>
</body>
</html>