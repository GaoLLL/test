<?php if (!defined('THINK_PATH')) exit();?><!--
<script language="javascript">
    function openmydoc(){
        var doc=new ActiveXObject("Word.Application");
        doc.visible=true;
        doc.Documents.Open("/Uploads/2019-01-17/5c402a286e4df.doc");
    }
</script>
</head>
<body>
<input type="button" value="ok" name="b1" onclick="openmydoc()" type="activxobject(word.application)" />
<a href="https://view.officeapps.live.com/op/embed.aspx?src=http://wq.lagewa.com/Uploads/2019-01-17/5c402a286e4df.doc" type="application/ms-word">打开word </a>
<a href="/Uploads/2019-01-17/5c402a286e4df.doc" target=_blank>clickme </a>
-->
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>税务文书详情</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body>
<div class="tax-documents-down">
    <p>文档下载地址：</p>
    <span><?php echo ($downurl); ?></span>
</div>
<div class="tax-documents-detail">
    <iframe src="<?php echo ($iframeurl); ?>" width="100%" height="1200px"></iframe>
</div>
<div class="tax-documents-down-btn">
    <a href="<?php echo ($downurl); ?>">下载表格</a>
</div>
</body>
</html>