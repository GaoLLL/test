<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
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
            <td align="right" height="50" width="50%"><label>公告名称：</label></td>
            <td align="left">
                <input name="title" value="" id="title" class="input-text radius" placeholder="公告名称" style="width:100%;height: 30px">
                <input name="addtime" type="hidden" value="<?php echo time();?>" />
            </td>
        </tr>

        <!--<tr>
            <td align="right" height="50" width="50%"><label>上传图片：</label></td>
            <td align="left">
                <input type="hidden" name="img" id="imgs" />
                <div id="div_banner">
                    <img src="" id="show" width="100" height="50">
                    <input type="button" id="uploadButton" value="上传图片" />
                    &lt;!&ndash;<m style="color:red">建议图片大小为1400px*700px</m>&ndash;&gt;
                </div>
            </td>
        </tr>


        <tr>
            <td align="right" height="50" width="50%"><label>排序：</label></td>
            <td align="left">
                <input type="text" id="sorts" name="sorts" value="0" style="width:50px;height: 30px;line-height: 30px;text-align: center;" />
            </td>
        </tr>-->



        <tr>
            <td align="right" height="50" width="50%"><label>公告详情：</label></td>
            <td>
                <textarea name="content" style="width:800px;height:400px;visibility:hidden;"></textarea>
                <p style="display:none;">
                    <input type="button" name="getHtml" value="取得HTML" />
                    <input type="button" name="isEmpty" value="判断是否为空" />
                    <input type="button" name="getText" value="取得文本(包含img,embed)" />
                    <input type="button" name="selectedHtml" value="取得选中HTML" />
                    <br />
                    <br />
                    <input type="button" name="setHtml" value="设置HTML" />
                    <input type="button" name="setText" value="设置文本" />
                    <input type="button" name="insertHtml" value="插入HTML" />
                    <input type="button" name="appendHtml" value="添加HTML" />
                    <input type="button" name="clear" value="清空内容" />
                    <input type="reset" name="reset" value="Reset" />
                </p>

            </td>
        </tr>

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

    function clearNoNum(obj){
        obj.value = obj.value.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符
        obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的
        obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
        obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数
        if(obj.value.indexOf(".")< 0 && obj.value !=""){
            obj.value= parseFloat(obj.value);
        }
    }



    var editor;

    KindEditor.ready(function(K) {
        /*var uploadbutton = K.uploadbutton({
            button: K('#uploadButton')[0],
            fieldName: 'imgFile',
            url: '/admin/base/upload',
            afterUpload: function(data) {
                if (data.code === 1) {

                    K('#show').attr('src', data.data);
                    $("#imgs").val(data.data)

                } else {
                    alert(data.msg);
                }
            },
            afterError: function(str) {
                alert('自定义错误信息: ' + str);
            }
        });
        uploadbutton.fileBox.change(function(e) {
            uploadbutton.submit();
        });*/

        editor = K.create('textarea[name="content"]', {
            allowFileManager: true,
            afterBlur: function () { this.sync(); }
        });
        K('input[name=sub]').click(function(e) {
            var e = editor.html();
           

        });
        K('input[name=isEmpty]').click(function(e) {
            alert(editor.isEmpty());
        });
        K('input[name=getText]').click(function(e) {
            alert(editor.text());
        });
        K('input[name=selectedHtml]').click(function(e) {
            alert(editor.selectedHtml());
        });
        K('input[name=setHtml]').click(function(e) {
            editor.html('<h3>Hello KindEditor</h3>');
        });
        K('input[name=setText]').click(function(e) {
            editor.text('<h3>Hello KindEditor</h3>');
        });
        K('input[name=insertHtml]').click(function(e) {
            editor.insertHtml('<strong>插入HTML</strong>');
        });
        K('input[name=appendHtml]').click(function(e) {
            editor.appendHtml('<strong>添加HTML</strong>');
        });
        K('input[name=clear]').click(function(e) {
            editor.html('');
        });
    });

 
    $(document).ready(function () {
        $("#div_banner").show();
      
        $("#isbanner").bind("click", function () {
            if ($(this).prop('checked')) {
                $(this).val(1);
                //$("#div_banner").show();
            } else {
                $(this).val(0);
                //$("#div_banner").hide();
            }

        });

    });  
     
       

    
</script>