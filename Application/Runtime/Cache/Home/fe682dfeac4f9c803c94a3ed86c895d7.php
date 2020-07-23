<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>我的印章</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body style="background-color: #EEEEEE;">
<?php if(($info["seal"]) == ""): ?><div class="no-seal" style="display: block;">
    <img src="/files/images/no_seal.png">
    <p>您还没有印章</p>
</div><?php endif; ?>
<?php if(($info["seal"]) != ""): ?><ul class="seal-list seal-cur">
        <li>
            <img src="<?php echo ($info["seal"]); ?>">
            <p><?php echo ($info["sealname"]); ?></p>
        </li>
    </ul><?php endif; ?>
</body>
</html>