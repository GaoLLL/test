<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>文件预览</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <link type="text/css" rel="stylesheet" href="/files/swiper/css/swiper.min.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="/files/swiper/js/swiper.min.js"></script>
    <script type="text/javascript" src="/files/js/pinchzoom.js"></script>
</head>

<body>
<div class="swiper-container sign-document-preview">
    <div class="swiper-pagination"></div>
    <div class="swiper-wrapper sign-document-preview-cont">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                <img src="<?php echo ($vo["img"]); ?>">
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: {
            el: '.swiper-pagination',
            type: 'fraction',
        },
    });
</script>
<div class="index-footer-height"></div>
<ul class="index-footer">
    <li>
        <a href="/home/sendsign/forminfo/id/<?php echo ($id); ?>">
            <img src="/files/images/index-footer-icon-03.png">
            <span>文件详情</span>
        </a>
    </li>
    <li>
        <a href="/home/sendsign/imginfo/id/<?php echo ($id); ?>" class="index-footer-cur">
            <img src="/files/images/index-footer-icon-04-a.png">
            <span>文件预览</span>
        </a>
    </li>
</ul>
</body>
</html>