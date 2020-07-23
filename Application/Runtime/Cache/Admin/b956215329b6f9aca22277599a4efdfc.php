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
        <i class="fa fa-file-text"></i> 全局菜单管理设置，修改后需要清除缓存！图标查看网址：http://fontawesome.dashgame.com
        <?php if(is_array($ActBtns)): $i = 0; $__LIST__ = $ActBtns;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$btn): $mod = ($i % 2 );++$i; if(($btn["action"]) == "add"): ?><a href="javascript:;" title="<?php echo ($btn["name"]); ?>" data-id="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["username"]); ?>"
                   class="<?php echo ($btn["action"]); ?> message-right">
                    <i class="fa fa-<?php echo ($btn["icon"]); ?>"></i>&nbsp;<?php echo ($btn["name"]); ?>&nbsp;
                </a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </nav>
    <div class="page-container">
        <div class="mt-20">
            <table class="table table-border table-bordered table-bg table-hover table-sort">
                <thead>
                <tr class="text-c">
                    <th width="40">序号</th>
                    <th>类型</th>
                    <th>验证</th>
                    <th align="center">图标</th>
                    <th>名称</th>
                    <th>控制器</th>
                    <th>方法</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(empty($list)): ?><tr class="text-c va-m">
                        <td colspan="9">暂无符合条件的数据！</td>
                    </tr>
                    <?php else: ?>
                    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="text-c">
                            <td width="40"><span class="label label-success radius"><?php echo ($k); ?></span></td>
                            <td class="text-l">
                                <?php if(($vo["level"]) == "1"): ?><span class="label label-secondary radius">菜单</span><?php endif; ?>
                                <?php if(($vo["level"]) == "2"): ?><span class="label label-secondary radius">模型</span><?php endif; ?>
                                <?php if(($vo["level"]) == "3"): ?><span class="label label-secondary radius">方法</span><?php endif; ?>
                            </td>
                            <td class="text-l">
                                <?php if(($vo["verify"]) == "1"): ?><span class="label label-primary radius">是</span><?php endif; ?>
                                <?php if(($vo["verify"]) == "2"): ?><span class="label label-primary radius">否</span><?php endif; ?>
                            </td>
                            <td class="text-l"><i class="fa fa-<?php echo ($vo["icon"]); ?>"></i></td>
                            <?php if(empty($vo["number"])): ?><td class="text-l"><?php echo ($vo["name"]); ?></td>
                                <td class="text-l">------</td>
                                <td class="text-l">------</td><?php endif; ?>
                            <?php if(($vo["number"]) == "1"): ?><td class="text-l">├─ ─ ─ ─ ─<?php echo ($vo["name"]); ?></td>
                                <td class="text-l"><?php echo ($vo["controller"]); ?></td>
                                <td class="text-l"><?php echo ($vo["action"]); ?></td><?php endif; ?>
                            <?php if(($vo["number"]) == "2"): ?><td class="text-l">├ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─<?php echo ($vo["name"]); ?></td>
                                <td class="text-l"><?php echo ($vo["controller"]); ?></td>
                                <td class="text-l"><?php echo ($vo["action"]); ?></td><?php endif; ?>
                            <td class="td-manage">
                                <?php if(is_array($ActBtns)): $i = 0; $__LIST__ = $ActBtns;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$btn): $mod = ($i % 2 );++$i; if($btn['action'] != 'add' && $btn['action'] != 'alldel'): ?><a href="javascript:;" title="<?php echo ($btn["name"]); ?>" data-id="<?php echo ($vo["id"]); ?>"
                                           data-name="<?php echo ($vo["username"]); ?>" class="<?php echo ($btn["action"]); ?>">
                                            <i class="fa fa-<?php echo ($btn["icon"]); ?>"></i>&nbsp;
                                        </a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
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
                content: '/index.php/Admin/Menu/add',
                btn: ['确定', '关闭'],
                maxmin: true,
                yes: function () {
                    var form = layer.getChildFrame('form', index);
                    if (flag == 2) {
                        return;
                    }
                    flag = 2;
                    $.post('/index.php/Admin/Menu/add', form.serialize(), function (data) {
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
                content: '/index.php/Admin/Menu/save/id/' + id,
                btn: ['确定', '关闭'],
                maxmin: true,
                yes: function () {
                    var form = layer.getChildFrame('form', index);
                    if (flag == 2) {
                        return;
                    }
                    flag = 2;
                    $.post('/index.php/Admin/Menu/save', form.serialize(), function (data) {
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
                    $.post('/index.php/Admin/Menu/del', {id: id}, function (data) {
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
                    $.post('/index.php/Admin/Menu/alldel', $('form').serialize() , function (data) {
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