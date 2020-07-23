<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <LINK rel="Bookmark" href="/favicon.ico" >
    <LINK rel="Shortcut Icon" href="/Public/admin//favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/admin/lib/html5.js"></script>
    <script type="text/javascript" src="/Public/admin/lib/respond.min.js"></script>
    <script type="text/javascript" src="/Public/admin/lib/PIE_IE678.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/lib/Hui-iconfont/1.0.7/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Huploadify/Huploadify.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/Public/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/Public/ztree/zTreeStyle.css" />
    <link rel="stylesheet" type="text/css" href="/Public/admin/static/h-ui.admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/Public/css/page.css" />
    <link rel="stylesheet" type="text/css" href="/Public/kindeditor/themes/default/default.css" />

    <!--[if IE 6]>
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>后台管理系统</title>
</head>
<body>

<ul id="ztree" class="ztree"></ul>
<div style="position:absolute;bottom: 0;right:10px;">
    <button class="save btn btn-success radius" data-id="<?php echo ($_GET['id']); ?>">保存</button>
    <button class="btn btn-secondary radius closee" data-id="<?php echo ($_GET['id']); ?>">关闭</button>
</div>
<script type="text/javascript" src="/Public/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/Public/admin/lib/layer3/layer.js"></script>
<script type="text/javascript" src="/Public/admin/lib/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/Public/Huploadify/jquery.Huploadify.js"></script>
<script type="text/javascript" src="/Public/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/Public/admin/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/Public/admin/static/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="/Public/ztree/jquery.ztree.all.js"></script>
<script type="text/javascript" src="/Public/js/md5.js"></script>
<script type="text/javascript" src="/Public/kindeditor/kindeditor-all-min.js"></script>
</body>
</html>
<script>
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "belongid",
                rootPId: 0
            }
        },
        check: {
            enable: true,
            chkStyle: "checkbox",
            chkboxType: {"Y": "ps", "N": "ps"}
        }
    };
    var zTreeNodes = [
        <?php echo ($str); ?>
    ];
    var index = parent.layer.getFrameIndex(window.name);
    var zTreeObj = $.fn.zTree.init($("#ztree"), setting, zTreeNodes);
    $('button.save').click(function () {
        var id = $(this).data('id');
        var nodes = zTreeObj.transformToArray(zTreeObj.getNodes());
        var Pso = new Object();
        $.each(nodes, function (i, v) {
            Pso[i] = {'name': v.name, 'id': v.id, 'checked': v.checked};
        });
        $.post('/index.php/Admin/Group/rbac', {data: Pso, id: id}, function (data) {
            if (data.status == 1) {
                layer.msg(data.msg, {
                    time: 2000,
                    //shade: [0.8, '#393D49'],
                    end: function () {
                        parent.layer.close(index);
                    }
                });
            } else {
                layer.msg(data.msg, function () {
                })
            }
        }, 'json');
    });
    $('button.closee').click(function () {
        parent.layer.close(index);
    })
</script>