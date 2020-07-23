<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>签署文件详情</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body>
<div class="sign-initiate-man">
    <p class="sign-initiate-man-name">
        <img src="/files/images/header-img.png">
        <span><?php echo ($username); ?></span>
        <m>发起时间：<?php echo ($start_time); ?></m>
    </p>
    <p class="sign-initiate-man-tips">
        <span>发起</span>
    </p>
</div>
<div class="sign-document-info">
    <p class="sign-document-info-title"><?php echo ($name); ?></p>
    <p class="sign-document-info-tips">
        <?php if(($status) == "1"): ?><span class="sign-document-info-wait">签署中</span><?php endif; ?>
        <?php if(($status) == "2"): ?><span class="sign-document-info-completed">已完成</span><?php endif; ?>
        <?php if(($status) == "3"): ?><span class="sign-document-info-refuse">已拒签</span><?php endif; ?>
        <?php if(($status) == "4"): ?><span class="sign-document-info-past">已过期</span><?php endif; ?>
    </p>
    <p class="sign-document-info-date">结束时间：<?php echo ($end_time); ?></p>
</div>
<ul class="sign-document-step">
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <div class="sign-document-step-cont">
                <div class="sign-document-step-number">
                    <span><?php echo ($vo["orders"]); ?></span>
                </div>
                <div class="sign-document-step-text">
                    <p class="sign-document-step-name">
                        <span><?php echo ($vo["username"]); ?></span>
                        <span class="sign-document-step-label">
                            <?php if(($vo["type"]) == "1"): ?>签名<?php endif; ?>
                            <?php if(($vo["type"]) == "2"): ?>印章<?php endif; ?>
                        </span>
                    </p>
                    <div class="sign-document-step-tel">
                        <p>
                            <span><?php echo ($vo["tel"]); ?></span>
                            <?php if(($vo["issign"]) == "1"): ?><m class="sign-document-step-wait">签署中</m><?php endif; ?>
                            <?php if(($vo["issign"]) == "2"): ?><m class="sign-document-step-completed">已完成</m><?php endif; ?>
                            <?php if(($vo["issign"]) == "3"): ?><m class="sign-document-step-refuse">已拒签</m>
                                <p class="refusal-reason"><?php echo ($username); ?>已被拒签，拒签理由：<?php echo ($vo["remark"]); ?></p><?php endif; ?>
                            <?php if(($vo["issign"]) == "4"): ?><m class="sign-document-step-nostart">已过期</m><?php endif; ?>
                            <?php if(($vo["issign"]) == "5"): ?><m class="sign-document-step-wait">未开始</m><?php endif; ?>
                        </p>
                        <?php if($vo['issign']==1 && $vo['uid'] == $uid && $status !=4){ ?>
                            <a href="/home/sendsign/signnamesign/type/<?php echo ($vo["type"]); ?>/id/<?php echo ($id); ?>/orders/<?php echo ($vo["orders"]); ?>" class="sign-document-step-sign">签署</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>

   <!-- <li>
        <div class="sign-document-step-cont">
            <div class="sign-document-step-number">
                <span>2</span>
            </div>
            <div class="sign-document-step-text">
                <p class="sign-document-step-name">
                    <span>姓名</span>
                    <span class="sign-document-step-label">签字</span>
                    <span class="sign-document-step-seal"></span>
                </p>
                <div class="sign-document-step-tel">
                    <p>
                        <span>13312345678</span>
                        <m class="sign-document-step-wait">待签署</m>
                    </p>
                    <a href="sign_immediately.html" class="sign-document-step-sign">签署</a>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="sign-document-step-cont">
            <div class="sign-document-step-number">
                <span>3</span>
            </div>
            <div class="sign-document-step-text">
                <p class="sign-document-step-name">
                    <span>姓名</span>
                    <span class="sign-document-step-label">印章</span>
                    <span class="sign-document-step-seal">印章名称印章名称</span>
                </p>
                <div class="sign-document-step-tel">
                    <p>
                        <span>13312345678</span>
                        <m class="sign-document-step-wait">待签署</m>
                    </p>
                    <a href="immediate_stamp.html" class="sign-document-step-sign">签署</a>
                </div>
            </div>
        </div>
    </li>-->
</ul>
<div class="index-footer-height"></div>
<ul class="index-footer">
    <li>
        <a href="/home/sendsign/forminfo/id/<?php echo ($id); ?>" class="index-footer-cur">
            <img src="/files/images/index-footer-icon-03-a.png">
            <span>文件详情</span>
        </a>
    </li>
    <li>
        <a href="/home/sendsign/imginfo/id/<?php echo ($id); ?>">
            <img src="/files/images/index-footer-icon-04.png">
            <span>文件预览</span>
        </a>
    </li>
</ul>
</body>
</html>