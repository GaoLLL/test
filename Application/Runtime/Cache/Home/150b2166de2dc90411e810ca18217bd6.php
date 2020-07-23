<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>模板详情</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body>
<input id="imgid" type="hidden" value="<?php echo ($imgid); ?>">
<p class="template-detail-title"><?php echo ($name); ?></p>
<ul class="signing-step-list2">
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <div class="signing-step-cont2">
                <div class="signing-step-number2">
                    <span><?php echo ($vo["orders"]); ?></span>
                </div>
                <div class="signing-step-text3">
                    <p class="signing-step-name2">
                        <span><?php echo ($vo["username"]); ?></span>
                        <?php if(($vo["type"]) == "1"): ?><span class="signing-step-label2">签名</span><?php endif; ?>
                        <?php if(($vo["type"]) == "2"): ?><span class="signing-step-seal2">印章</span>
                            <m><?php echo ($vo["sealname"]); ?></m><?php endif; ?>
                    </p>
                    <div class="signing-step-tel2">
                        <p>
                            <span><?php echo ($vo["tel"]); ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>

   <!-- <li>
        <div class="signing-step-cont2">
            <div class="signing-step-number2">
                <span>2</span>
            </div>
            <div class="signing-step-text3">
                <p class="signing-step-name2">
                    <span>姓名</span>
                    <span class="signing-step-seal2">印章</span>
                    <m>印章名称印章名称</m>
                </p>
                <div class="signing-step-tel2">
                    <p>
                        <span>13312345678</span>
                    </p>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="signing-step-cont2">
            <div class="signing-step-number2">
                <span>3</span>
            </div>
            <div class="signing-step-text3">
                <p class="signing-step-name2">
                    <span>姓名</span>
                    <span class="signing-step-label2">签名</span>
                </p>
                <div class="signing-step-tel2">
                    <p>
                        <span>13312345678</span>
                    </p>
                </div>
            </div>
        </div>
    </li>
    <li>
        <div class="signing-step-cont2">
            <div class="signing-step-number2">
                <span>4</span>
            </div>
            <div class="signing-step-text3">
                <p class="signing-step-name2">
                    <span>姓名</span>
                    <span class="signing-step-label2">签名</span>
                </p>
                <div class="signing-step-tel2">
                    <p>
                        <span>13312345678</span>
                    </p>
                </div>
            </div>
        </div>
    </li>-->
</ul>
<?php if(($htid) == ""): ?><div class="tax-documents-down-btn">
            <a href="/home/sendsign/signdo/id/<?php echo ($imgid); ?>/mid/<?php echo ($mid); ?>">确定</a>
        </div>
    <?php else: ?>
        <div class="tax-documents-down-btn">
            <a href="/home/sendsign/zgysigndo/htid/<?php echo ($htid); ?>/id/<?php echo ($imgid); ?>/mid/<?php echo ($mid); ?>">确定</a>
        </div><?php endif; ?>

</body>
</html>