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
            <td align="left"><input name="username" value="" required class="input-text radius" placeholder="输入人员名称" style="width:200px;height: 30px"></td>
        </tr>
      <!--  <tr>
            <td align="right" height="50" width="50%"><label>联系电话：</label></td>
            <td align="left"><input name="tel" value="" required class="input-text radius" placeholder="输入联系电话" style="width:200px;height: 30px"></td>
        </tr>-->

        <tr>
            <td align="right" height="50" width="50%"><label>身份证号：</label></td>
            <td align="left"><input name="idnumber" value=""  required class="input-text radius" placeholder="输入身份证号" style="width:200px;height: 30px"></td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>所属层级：</label></td>
            <td align="left">
                <input name="name" value="" class="input-text radius" placeholder="选择所属层级" id="cj" style="width:200px;height: 30px" disabled>
                <input type="hidden" name="pid" value="" id="pid"/>
                <span style="margin-left: 10px;color:grey;cursor:pointer;"id="choise">选择层级</span>
            </td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>所属部门：</label></td>
            <td align="left"><input name="bm" value="" class="input-text radius" placeholder="输入所属部门" style="width:200px;height: 30px"></td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>所属岗位：</label></td>
            <td align="left"><input name="worker" value="" class="input-text radius" placeholder="输入所属岗位" style="width:200px;height: 30px"></td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>是否为专管员：</label></td>
            <td align="left">
                <select class="input-text radius" style="width:200px;height: 30px" id="" name="isbest">
                    <option value="2">否</option>
                    <option value="1">是</option>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right" height="50" width="50%"><label>是否有加盖印章权限：</label></td>
            <td align="left">
                <select class="input-text radius" style="width:200px;height: 30px" id="choiseseal">
                    <option>请选择</option>
                    <option value="1">是</option>
                    <option value="2">否</option>
                </select>
                <span style="margin-left: 10px;color:grey;cursor:pointer;display: none;" id="seal">选择印章</span>
                <input type="hidden" value="" name="sid" id="yzstr">
            </td>
        </tr>
        <tr id="rqshow" style="display: none;">
            <td align="right" height="50" width="50%"><label>已选择印章：</label></td>
            <td align="left" id="rq"></td>
        </tr>
        <input type="hidden" name="status" value="1"/>
        <input type="hidden" name="creat_time" value="<?php echo time();?>"/>
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
            }else{
                $("#seal").hide();
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