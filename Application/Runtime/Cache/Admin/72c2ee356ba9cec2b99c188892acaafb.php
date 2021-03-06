<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>组织架构</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/Public/framework/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Public/css/font-awesome.min.css" />

    <link href="/Public/framework/css/animate.min.css" rel="stylesheet">
    <link href="/Public/framework/css/style.min.css?v=4.1.0" rel="stylesheet">
    <link href="/Public/framework/layui3/css/layui.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Public/admin/lib/Hui-iconfont/1.0.7/iconfont.css" />

</head>
<style>
    .btn-success.btn-outline{
        margin-left: 10px;
    }
</style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins col-sm-5">
        <div class="ibox-title">
            <h5>组织架构</h5>
        </div>
        <div class="ibox-content">
            <div class="form-group">
                <?php if(($show) == "1"): ?><button class="btn btn-outline btn-primary" type="button" id="addNode">添加顶级组织</button><?php endif; ?>
                <button class="btn btn-outline btn-success" type="button" onclick="window.location.reload();">刷新组织架构</button>
            </div>

            <div class="ibox-content">
                <div class="col-sm-6" style="width: 100%;">
                    <ul id="tree"></ul>
                </div>
                <div class="col-sm-6">
                    <div id="event_output"></div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


<!-- 添加节点 -->
<div class="ibox-content" id="node_box" style="display: none">
    <form class="form-horizontal m-t" method="post" action="/admin/framework/add" id="addForm">
        <input type="hidden" class="form-control" value="0" name="type_id" id="pid">
        <div class="form-group">
            <label class="col-sm-3 control-label">所属上级：</label>
            <div class="input-group col-sm-7">
                <input id="show_pid" type="text" class="form-control" value="顶级节点" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">层级名称：</label>
            <div class="input-group col-sm-7">
                <input id="node_name" type="text" class="form-control" name="node_name" required="" aria-required="true">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-8">
                <button class="btn btn-primary" type="submit">提交</button>
            </div>
        </div>
    </form>
</div>
<!-- 添加节点 -->

<!-- 编辑节点 -->
<div class="ibox-content" id="edit_box" style="display: none">
    <form class="form-horizontal m-t" method="post" action="/admin/framework/save" id="editForm">
        <input type="hidden" name="id" id="id"/>
        <div class="form-group">
            <label class="col-sm-3 control-label">层级名称：</label>
            <div class="input-group col-sm-7">
                <input id="e_node_name" type="text" class="form-control" name="node_name" required="" aria-required="true">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-8">
                <button class="btn btn-primary" type="submit">提交</button>
            </div>
        </div>
    </form>
</div>
<!-- 添加节点 -->

<!-- 节点操作询问层 -->
<div class="ibox-content" id="ask_box" style="display: none">
    <div class="form-horizontal m-t">
        <div class="form-group" style="text-align: center">
            <?php if(is_array($ActBtns)): $i = 0; $__LIST__ = $ActBtns;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><button class="btn btn-outline btn-success" type="button" id="<?php echo ($vo["action"]); ?>Node">
                    <i class="<?php echo ($vo["icon"]); ?>"></i>
                    <?php echo ($vo["name"]); ?>
                </button><?php endforeach; endif; else: echo "" ;endif; ?>
           <!-- {if authCheck('node/nodeadd')}
            <button class="btn btn-outline btn-success" type="button" id="addsubNode">
                <i class="fa fa-plus"></i>
                添加子节点
            </button>
            {/if}
            {if authCheck('node/nodeedit')}
            <button class="btn btn-outline btn-primary" type="button" id="editNode">
                <i class="fa fa-edit"></i>
                编辑节点
            </button>
            {/if}
            {if authCheck('node/nodedel')}
            <button class="btn btn-outline btn-danger" type="button" id="delNode">
                <i class="fa fa-trash-o"></i>
                删除节点
            </button>
            {/if}-->
        </div>
    </div>
</div>
<!-- 节点操作询问层 -->

<!-- End Panel Other -->
<script src="/Public/framework/js/jquery.min.js?v=2.1.4"></script>
<script src="/Public/framework/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/Public/framework/js/content.min.js?v=1.0.0"></script>
<script src="/Public/framework/layui3/layui.js"></script>
<script src="/Public/framework/js/jquery.form.js"></script>
<script type="text/javascript">
    var node_del_url = "/admin/framework/del";
    var box;
    var nowNode = null;

    $(function(){

        getTree();

        $("#addNode").click(function(){
            $("#control_name").val('#');
            $("#action_name").val('#');
            $("#pid").val(0);
            $("#show_pid").val('顶级节点');

            layui.use('layer', function(){
                box = layer.open({
                    type: 1,
                    title: '添加顶级节点',
                    anim: 2,
                    skin: 'layui-layer-molv', //加上边框
                    area: ['620px', '440px'], //宽高
                    content: $('#node_box')
                });
            });
        });

        $("#addsubNode").click(function(){
            layer.close(box);
            $('#show_pid').val(nowNode.name);
            $('#pid').val(nowNode.id);
            $("#control_name").val('');
            $("#action_name").val('');

            layui.use('layer', function(){
                box = layer.open({
                    type: 1,
                    title: '添加 ' + nowNode.name + ' 的下属',
                    anim: 2,
                    skin: 'layui-layer-molv', //加上边框
                    area: ['620px', '440px'], //宽高
                    content: $('#node_box')
                });
            });
        });

        $("#saveNode").click(function(){
            layer.close(box);
            $("#id").val(nowNode.id);
            $("#e_node_name").val(nowNode.name);
            $("#e_control_name").val(nowNode.control_name);
            $("#e_action_name").val(nowNode.action_name);
            $("#e_style").val(nowNode.style);

            var _option1 = '<option value="1" selected>否</option><option value="2">是</option>';
            var _option2 = '<option value="1">否</option><option value="2" selected>是</option>';
            if(1 == nowNode.is_menu){
                $("#e_is_menu").html(_option1);
            }else{
                $("#e_is_menu").html(_option2);
            }

            layui.use('layer', function(){
                box = layer.open({
                    type: 1,
                    title: '编辑  ' + nowNode.name + '  层级',
                    anim: 2,
                    skin: 'layui-layer-molv', //加上边框
                    area: ['620px', '400px'], //宽高
                    content: $('#edit_box')
                });
            });
        });

        $("#delNode").click(function(){
            layer.close(box);
            if(nowNode.children.length > 0){
                layer.alert('该层级下有子层级，不可删除', {icon:2, title:'失败提示', closeBtn:0, anim:6});
                return false;
            }

            //询问框
            var index = layer.confirm('确定要删除' + nowNode.name + '？', {
                icon: 3,
                title: '友情提示',
                btn: ['确定','取消'] //按钮
            }, function(){

                $.post(node_del_url, {id : nowNode.id},function(res){
                    layer.close( index );
                    if( 1 == res.code ){
                        $("#tree").empty();
                        getTree();
                    }else if(111 == res.code){
                        window.location.reload();
                    }else{
                        layer.alert(res.msg, {icon:2});
                    }
                },'json');
            }, function(){

            });
        });


        // 添加节点
        var options = {
            dataType:"json",
            beforeSubmit:showStart,
            success:showSuccess
        };

        $('#addForm').submit(function(){
            $(this).ajaxSubmit(options);
            return false;
        });

        // 编辑节点
        $('#editForm').submit(function(){
            $(this).ajaxSubmit(options);
            return false;
        });
    });

    function getTree(){
        layui.use(['tree', 'layer'], function(){
            var layer = layui.layer;

            $.post("/admin/framework/index", function(res){
                if(111 == res.code){
                    window.location.reload();
                }
                layui.tree({
                    elem: '#tree'
                    ,nodes: res.data
                    ,click: function(node){
                        layui.use('layer', function(){
                            box = layer.open({
                                type: 1,
                                title: '您要如何操作该层级',
                                anim: 2,
                                skin: 'layui-layer-molv', //加上边框
                                area: ['400px', '150px'], //宽高
                                content: $('#ask_box')
                            });
                        });

                        nowNode = node;
                    }
                });
            },'json');
        });
    }

    // 添加相关的 js
    var index = '';
    function showStart(){
        index = layer.load(0, {shade: false});
        return true;
    }

    function showSuccess(res){
        layui.use('layer', function(){
            var layer = layui.layer;

            layer.ready(function(){
                layer.close( index );
                layer.close( box );
                if( 1 == res.code ){
                    $("#node_name").val('');
                    $("#route").val('');
                    $("#tree").empty();
                    getTree();
                }else if(111 == res.code){
                    window.location.reload();
                }else{
                    layer.alert(res.msg, {icon:2});
                }
            });
        });
    }

</script>
</body>
</html>