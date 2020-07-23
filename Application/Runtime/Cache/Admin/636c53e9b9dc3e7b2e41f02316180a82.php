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

<div>
    <nav class="breadcrumb">
        <a href="javascript:;" class=" message-right" id="qdxz">
            <i class="fa fa-check-square-o"></i>&nbsp;确定选择&nbsp;
        </a>
    </nav>
    <form action="/index.php/Admin/Users/seal">
        <div class="search_list">
        <span class="search_list_content">
            印章名称：
            <input type="text" name="title" value="<?php echo ($search['title']); ?>" width="50" class="search_input"/>
        </span>
            <span class="search_list_content">
            &nbsp;&nbsp;<input type="submit" value="查询" class="btn_fa_css btn btn-primary" style="margin:0;"/>
        </span>
        </div>
        <div class="page-container">
            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="40"></th>
                        <th width="40">序号</th>
                        <th>缩略图片</th>
                        <th>印章名称</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(empty($list)): ?><tr class="text-c va-m">
                            <td colspan="8">暂无符合条件的数据！</td>
                        </tr>
                        <?php else: ?>
                        <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="text-c">
                                <td width="40"><input name="id" type="radio" value="<?php echo ($vo["id"]); ?>" data-img="<?php echo ($vo["img"]); ?>" class="nowxz"></td>
                                <td width="40"><span class="label label-success radius"><?php echo ($k); ?></span></td>
                                <td class="text-l" style="cursor:pointer;"><img src="<?php echo ($vo["img"]); ?>" width="100" height="50" class="imgshow" /></td>
                                <td class="text-l"><?php echo ($vo["name"]); ?></td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </tbody>
                </table>
                <div class="scott">
                    <?php if(is_array($ActBtns)): $i = 0; $__LIST__ = $ActBtns;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$btn): $mod = ($i % 2 );++$i; if(($btn["action"]) == "alldel"): ?><a href="javascript:;" class="<?php echo ($btn["action"]); ?>" style="float:left;">
                                <?php echo ($btn["name"]); ?>
                            </a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    <p style="margin-bottom:15px;">共<?php echo ($count); ?>条记录/每页显示<input type="text" size="5" name="pagesize" value="<?php echo ($pagesize); ?>" style="text-align:center;"/>条&nbsp;<input
                            type="submit" value="GO"/></p><?php echo ($page); ?>
                </div>
            </div>
        </div>
    </form>
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
    $('.rbac').click(function () {
        var id = $(this).data('id');
        layer.open({
            type: 2,
            title: '管理员授权管理',
            area: ['100vw', '100vh'],
            content: '/index.php/Admin/Users/rbac/id/' + id + '.html'
        })
    });
    $(function () {
        $(".imgshow").click(function(){
            var img = $(this).attr('src');
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                area: ['50%','50%'],
                skin: 'layui-layer-nobg', //没有背景色
                shadeClose: true,
                content: "<img src='"+img+"' width='100%' height='100%'>"
            });
        })

        $("#qdxz").on('click',function(){
            var _this = $('input:radio[name="id"]:checked');
            var str = _this.val();

            var img = "<span style='margin-left: 10px' ><img src='"+_this.data('img')+"' width='100' height='100' onclick='showimg(this)' style='cursor:pointer;'></span>";
           /* $.each($('input:checkbox:checked'),function(){
                if($(this).val()!=''){
                    str+=$(this).val()+',';
                    img+="<span style='margin-left: 10px' ><img src='"+$(this).data('img')+"' width='100' height='100' onclick='showimg(this)' style='cursor:pointer;'></span>"
                }
            });*/
            parent.$("#rqshow").show();

            parent.$("#yzstr").val(str);
            parent.$("#rq").html(img);
            var indexgg = parent.layer.getFrameIndex(window.name);
            parent.layer.close(indexgg);
            console.log(str);
            console.log(img);
        })
    })

</script>