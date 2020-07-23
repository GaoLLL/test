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
        <i class="fa fa-home"></i> 人员管理
        <?php if(is_array($ActBtns)): $i = 0; $__LIST__ = $ActBtns;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$btn): $mod = ($i % 2 );++$i; if(($btn["action"]) == "add"): ?><a href="javascript:;" title="<?php echo ($btn["name"]); ?>" data-id="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["username"]); ?>"
                   class="<?php echo ($btn["action"]); ?> message-right">
                    <i class="fa fa-<?php echo ($btn["icon"]); ?>"></i>&nbsp;<?php echo ($btn["name"]); ?>&nbsp;
                </a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </nav>
    <form action="/index.php/Admin/Users/zrrindex">
        <div class="search_list">
        <span class="search_list_content">
            电话号：
            <input type="text" name="tel" value="<?php echo ($search['tel']); ?>" width="50" class="search_input"/>
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
                        <th width="40">序号</th>
                        <th>姓名</th>
                        <th>联系电话</th>
                        <!--<th>所属层级</th>
                        <th>所属岗位</th>
                        <th>是否有加盖印章权限</th>
                        <th width="100">操作</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(empty($list)): ?><tr class="text-c va-m">
                            <td colspan="8">暂无符合条件的数据！</td>
                        </tr>
                        <?php else: ?>
                        <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="text-c">
                                <td width="40"><span class="label label-success radius"><?php echo ($k); ?></span></td>
                                <td class="text-l" style="cursor:pointer;"><?php echo ($vo["username"]); ?></td>
                                <td class="text-l"><?php echo ($vo["tel"]); ?></td>
                                <!--<td class="text-l"><?php echo ($vo["pidname"]); ?></td>
                                <td class="text-l"><?php echo ($vo["worker"]); ?></td>
                                <td class="text-l">
                                    <?php if(($vo["sid"]) == "0"): ?>否<?php else: ?>是<?php endif; ?>
                                </td>-->
                                <td class="td-manage">
                                    <?php if(is_array($ActBtns)): $i = 0; $__LIST__ = $ActBtns;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$btn): $mod = ($i % 2 );++$i; if($btn['action'] != 'add' && $btn['action'] != 'alldel'): ?><a href="javascript:;" title="<?php echo ($btn["name"]); ?>" data-id="<?php echo ($vo["id"]); ?>"
                                               data-name="<?php echo ($vo["name"]); ?>" class="<?php echo ($btn["action"]); ?>">
                                                <i class="fa fa-<?php echo ($btn["icon"]); ?>"></i>&nbsp;
                                            </a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </tbody>
                </table>
                <div class="scott">
                    <?php if(is_array($ActBtns)): $i = 0; $__LIST__ = $ActBtns;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$btn): $mod = ($i % 2 );++$i; if(($btn["action"]) == "alldel"): ?><a href="javascript:;" class="<?php echo ($btn["action"]); ?>" style="float:left;">
                                <?php echo ($btn["name"]); ?>
                            </a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    <p style="margin-bottom:15px;">共<?php echo ($count); ?>条记录/每页显示<input type="text" size="5" name="pagesize"
                                                                           value="<?php echo ($pagesize); ?>"
                                                                           style="text-align:center;"/>条&nbsp;<input
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
    $(document).ready(function () {
        var flag = 1;
        /**
         * 添加
         */
        $('.add').click(function () {
            var index = layer.open({
                type: 2,
                title: '<i class="fa fa-plus-square-o" style="color: red;"> </i> 新增',
                area: ['700px', '470px'],
                fix: false, //不固定
                content: '/index.php/Admin/Users/add',
                btn: ['确定', '关闭'],
                maxmin: true,
                yes: function () {
                    var form = layer.getChildFrame('form', index);
                    if (flag == 2) {
                        return;
                    }
                    flag = 2;
                    $.post('/index.php/Admin/Users/add', form.serialize(), function (data) {
                        if (data.status == 1) {
                            layer.msg(data.msg, {
                                time: 2000,
                                end: function () {
                                    layer.close(index);
                                    window.location.reload();
                                }
                            })
                        } else {
                            layer.msg(data.msg, function () {
                                flag = 1;
                            });
                        }
                    }, 'json')
                }
            });
            layer.full(index);
        });
        /**
         * 修改
         */
        $('.save').click(function () {
            var title = $(this).data('name');
            var id = $(this).data('id');
            var index = layer.open({
                type: 2,
                title: '<i class="fa fa-plus-square-o" style="color: red;"> </i> 编辑' + title,
                area: ['700px', '470px'],
                fix: false, //不固定
                content: '/index.php/Admin/Users/save/id/' + id,
                btn: ['确定', '关闭'],
                maxmin: true,
                yes: function () {
                    var form = layer.getChildFrame('form', index);
                    if (flag == 2) {
                        return;
                    }
                    flag = 2;
                    $.post('/index.php/Admin/Users/save', form.serialize(), function (data) {
                        if (data.status == 1) {
                            layer.msg(data.msg, {
                                time: 2000,
                                end: function () {
                                    layer.close(index);
                                    window.location.reload();
                                }
                            })
                        } else {
                            layer.msg(data.msg, function () {
                                flag = 1;
                            });
                        }
                    }, 'json')
                }
            });
            layer.full(index);
        });
        /**
         * 删除
         */
        $('.del').click(function () {
            var title = $(this).data('name');
            var id = $(this).data('id');
            var index = layer.msg('是否删除' + title + '? <br>删除后不可恢复，请谨慎操作！', {
                icon: 5,
                time: 0,
                shade: [0.8, '#393D49'],
                btn: ['删除', '取消'],
                yes: function () {
                    if(flag == 2){
                        return;
                    }
                    flag = 2;
                    $.post('/index.php/Admin/Users/del', {id: id}, function (data) {
                        if (data.status == 1) {
                            layer.msg(data.msg, {
                                time: 1000, end: function () {
                                    window.location.reload();
                                }
                            })
                        } else {
                            layer.msg(data.msg, function () {
                                flag = 1;
                            });
                        }
                    }, 'json');
                }
            });
        });
        /**
         * 删除选中
         */
        $('.alldel').click(function () {
            var index = layer.msg('是否确认删除' +  '? <br>删除后不可恢复，请谨慎操作！', {
                icon: 5,
                time: 0,
                shade: [0.8, '#393D49'],
                btn: ['删除', '取消'],
                yes: function () {
                    if(flag == 2){
                        return;
                    }
                    flag = 2;
                    $.post('/index.php/Admin/Users/alldel', $('form').serialize() , function (data) {
                        if (data.status == 1) {
                            layer.msg(data.msg, {
                                time: 1000, end: function () {
                                    window.location.reload();
                                }
                            })
                        } else {
                            layer.msg(data.msg, function () {
                                flag = 1;
                            });
                        }
                    }, 'json');
                }
            });
        });
    });
</script>
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
    })

</script>