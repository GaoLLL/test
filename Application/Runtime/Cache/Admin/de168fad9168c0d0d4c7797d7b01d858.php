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
    <table id="wai">
        <tr>
            <td align="right" height="50" width="50%"><label>模板名称：</label></td>
            <td align="left"><input name="name" value="<?php echo ($data["name"]); ?>" required class="input-text radius" placeholder="输入模板名称" style="width:200px;height: 30px"></td>
        </tr>
        <input type="hidden" value="<?php echo ($data["count"]); ?>" id="nownode"/>
        <?php if(is_array($data["big"])): $i = 0; $__LIST__ = $data["big"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="rwai<?php echo ($vo["orders"]); ?>">
                <td align="right" height="50" width="50%"><label style="color:red;">第<b><?php echo ($vo["orders"]); ?></b>签署人：</label></td>
                <td align="left">
                    <select class="input-text radius xzqx" name="big[1][type]" onchange="showbtn(this)" style="width:200px;height: 30px" data-biao="<?php echo ($vo["orders"]); ?>">
                        <option>请选择</option>
                        <option value="1" <?php if(($vo["type"]) == "1"): ?>selected<?php endif; ?>>签字</option>
                        <option value="2" <?php if(($vo["type"]) == "2"): ?>selected<?php endif; ?>>盖章</option>
                    </select>
                    <input type="hidden" value="<?php echo ($vo["uid"]); ?>" id="uid1" name="big[<?php echo ($vo["orders"]); ?>][uid]">
                    <input type="hidden" value="<?php echo ($vo["orders"]); ?>" id="orders1" name="big[<?php echo ($vo["orders"]); ?>][orders]">
                    <span id="nowshow<?php echo ($vo["orders"]); ?>"><?php echo ($vo["qm"]); ?></span>
                    <span style="margin-left: 10px;color:grey;cursor:pointer;display: none;" id="qm<?php echo ($vo["orders"]); ?>" onclick="xzqm(this)" data-biao="<?php echo ($vo["orders"]); ?>">选择签名</span>
                    <span style="margin-left: 10px;color:grey;cursor:pointer;display: none;" id="gz<?php echo ($vo["orders"]); ?>" onclick="xzgz(this)" data-biao="<?php echo ($vo["orders"]); ?>">选择盖章</span>

                        <span style="margin-left: 10px;color:grey;cursor:pointer;<?php if($i != count($data['big'])): ?>display:none<?php endif; ?>"onclick="delrows(this)" id="del<?php echo ($vo["orders"]); ?>" data-biao="<?php echo ($vo["orders"]); ?>">删除此步骤</span>

                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        <input type="hidden" name="status" value="1"/>
        <input type="hidden" name="creat_time" value="<?php echo time();?>"/>
    </table>
    <table>
        <tr>
            <td align="right" height="50" width="50%"><input type="button" value="新增步骤" class="btn_fa_css btn btn-primary"></td>
            <td align="left"></td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>">
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

        $(".btn_fa_css").on('click',function(){
            var nownode = $("#nownode").val();
            var nextnode= parseInt(nownode) + parseInt(1);
            $("#del"+nownode).hide()
            var type= "big["+nextnode+"][type]";
            var uid = "big["+nextnode+"][uid]";
            var orders="big["+nextnode+"][orders]";
            $("#nownode").val(nextnode);
            $("#wai").append('<tr id="rwai'+nextnode+'">' +
                '            <td align="right" height="50" width="50%"><label style="color:red;">第<b>'+nextnode+'</b>签署人：</label></td>' +
                '            <td align="left">' +
                '                <select class="input-text radius xzqx" name="'+type+'" onchange="showbtn(this)" style="width:200px;height: 30px" data-biao="'+nextnode+'">' +
                '                    <option>请选择</option>' +
                '                    <option value="1">签字</option>' +
                '                    <option value="2">盖章</option>' +
                '                </select>' +
                '                <span id="nowshow'+nextnode+'"></span>' +
                '                <input type="hidden" value="" id="uid'+nextnode+'" name="'+uid+'">                                       '+
                '                <input type="hidden" value="'+nextnode+'" id="orders'+nextnode+'" name="'+orders+'">                                      '+
                '                <span style="margin-left: 10px;color:grey;cursor:pointer;display: none;" id="qm'+nextnode+'" onclick="xzqm(this)" data-biao="'+nextnode+'">选择签名</span>' +
                '                <span style="margin-left: 10px;color:grey;cursor:pointer;display: none;" id="gz'+nextnode+'" onclick="xzgz(this)" data-biao="'+nextnode+'">选择盖章</span>' +
                '                <span style="margin-left: 10px;color:grey;cursor:pointer;"  onclick="delrows(this)" id="del'+nextnode+'" data-biao="'+nextnode+'">删除此步骤</span>'+
                '            </td>' +
                '        </tr>')
        })


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

    function showbtn(obj){
        var _this = $(obj);
        var now = _this.data('biao');
        var nowval = _this.val();

        console.log(now);
        console.log(nowval);
        if(nowval==1){
            $("#qm"+now).show();
            $("#gz"+now).hide();
        }else if(nowval==2){
            $("#gz"+now).show();
            $("#qm"+now).hide();
        }else{
            $("#gz"+now).hide();
            $("#qm"+now).hide();
        }
        $("#nowshow"+now).text('');
    }

    function xzqm(obj){
        var nowid = $(obj).data('biao');
        layer.open({
            type: 2,
            title: '选择层级',
            shadeClose: false,
            shade: 0.8,
            area: ['70%', '90%'],
            content: '/admin/sign/choise/nowid/'+nowid
        });
    }

    function xzgz(obj){
        var nowid = $(obj).data('biao');
        layer.open({
            type: 2,
            title: '选择印章',
            shadeClose: false,
            shade: 0.8,
            area: ['70%', '90%'],
            content: '/admin/sign/seal/nowid/'+nowid
        });
    }

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

    function delrows(obj){
        var o = $(obj).data('biao');
        if(parseInt(o) == 1){
            layer.msg('至少保留一个签署人,这个不能再删除了',{offset:'30%'})
            return false;
        }else{
            var nownode = $("#nownode").val();
            var nextnode= parseInt(nownode) - parseInt(1);
            $("#del"+nextnode).show();
            $("#nownode").val(nextnode);
            $("#rwai"+o).remove();
        }

    }
</script>