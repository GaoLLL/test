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

<form style="width:90%;text-align: center">
    <table>
        <tr>
            <td align="right" height="50" width="50%"><label>人员名称：</label></td>
            <td align="left"><input name="username" value="<?php echo ($data["username"]); ?>" required class="input-text radius" placeholder="输入人员名称" style="width:200px;height: 30px"></td>
        </tr>
        <!--<tr>
            <td align="right" height="50" width="50%"><label>联系电话：</label></td>
            <td align="left"><input name="tel" value="<?php echo ($data["tel"]); ?>" required class="input-text radius" placeholder="输入联系电话" style="width:200px;height: 30px"></td>
        </tr>-->

        <tr>
            <td align="right" height="50" width="50%"><label>身份证号：</label></td>
            <td align="left"><input name="idnumber" value="<?php echo ($data["idnumber"]); ?>"  required class="input-text radius" placeholder="输入身份证号" style="width:200px;height: 30px"></td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>所属层级：</label></td>
            <td align="left">
                <input name="name" value="<?php echo ($cj["node_name"]); ?>" class="input-text radius" placeholder="选择所属层级" id="cj" style="width:200px;height: 30px" disabled>
                <input type="hidden" name="pid" value="<?php echo ($data["pid"]); ?>" id="pid"/>
                <span style="margin-left: 10px;color:grey;cursor:pointer;" id="choise">选择层级</span>
            </td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>所属部门：</label></td>
            <td align="left"><input name="bm" value="<?php echo ($data["bm"]); ?>" class="input-text radius" placeholder="输入所属部门" style="width:200px;height: 30px"></td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>所属岗位：</label></td>
            <td align="left"><input name="worker" value="<?php echo ($data["worker"]); ?>" class="input-text radius" placeholder="输入所属岗位" style="width:200px;height: 30px"></td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>是否为专管员：</label></td>
            <td align="left">
                <select class="input-text radius" style="width:200px;height: 30px" id="" name="isbest">
                    <option value="2" <?php if(($data["isbest"]) == "2"): ?>selected<?php endif; ?> >否</option>
                    <option value="1" <?php if(($data["isbest"]) == "1"): ?>selected<?php endif; ?>>是</option>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>是否有加盖印章权限：</label></td>
            <td align="left">
                <select class="input-text radius" style="width:200px;height: 30px" id="choiseseal">
                    <option>请选择</option>
                    <option value="1" <?php if(($data["sid"]) > "0"): ?>selected<?php endif; ?> >是</option>
                    <option value="2" <?php if(($data["sid"]) == "0"): ?>selected<?php endif; ?>>否</option>
                </select>
                <span style="margin-left: 10px;color:grey;cursor:pointer;display:<?php if(($data["sid"]) == "0"): ?>none<?php else: ?>inline-block<?php endif; ?>;" id="seal">选择印章</span>
                <input type="hidden" value="<?php echo ($data["sid"]); ?>" name="sid" id="yzstr">
            </td>
        </tr>
        <tr id="rqshow" style="display:<?php if(($data["sid"]) == "0"): ?>none<?php else: ?>table-row<?php endif; ?>;">
            <td align="right" height="50" width="50%"><label>已选择印章：</label></td>
            <td align="left" id="rq">
                <?php echo ($img); ?>
            </td>
        </tr>
        <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>"/>
    </table>
</form>

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
    $(function(){
        $("#choise").click(function(){
            layer.open({
                type: 2,
                title: '选择层级',
                shadeClose: false,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/users/choise'
            });
        })

        $("#choiseseal").on('change',function(){
            var val = $(this).val();
            if(val == 1){
                $("#seal").show();
                $("#rqshow").show();
            }else{
                $("#seal").hide();
                $("#rqshow").hide();
                $("#yzstr").val('');
                $("#rq").html('');
            }
        })
        $("#seal").on('click',function(){
            layer.open({
                type: 2,
                title: '选择印章',
                shadeClose: false,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/users/seal'
            });
        })
    })

    function showimg(obj){

        var img = $(obj).attr('src');
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            area: ['50%','50%'],
            skin: 'layui-layer-nobg', //没有背景色
            shadeClose: true,
            content: "<img src='"+img+"' width='100%' height='100%' >"
        });
    }
</script>