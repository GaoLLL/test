<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>模板列表</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <link rel="stylesheet" type="text/css" href="/Public/layui/css/layui.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script src="/Public/layui/layui.js" type="text/javascript"></script>
</head>

<body>
<div class="tax-documents-search">
    <input type="text" placeholder="请输入搜索模板名称" id="search">
    <a href="javascript:void(0);" class="tax-documents-search-btn" onclick="oneshow()">搜索</a>
</div>
<div class="search-height"></div>
<ul class="template-list" id="test">

</ul>
<input type="hidden" id="imgid" value="<?php echo ($imgid); ?>"/>
<input type="hidden" id="htid" value="<?php echo ($htid); ?>"/>
<script>

    $(function(){
        setTimeout(function(){
            oneshow();
        },100)
    })
    function oneshow(){
        var title = $("#search").val();
        var imgid = $("#imgid").val();
        var htid = $("#htid").val();
        layui.use('flow', function () {
            var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
            var flow = layui.flow;
            $("#test").html('');
            if(title !=''){
                flow.load({
                    elem: '#test' //指定列表容器
                    , scrollElem: '.template-list'
                    , isAuto: true
                    , end: '<span style="color:#07aefc;font-size: 14px;">已经到底</span>'
                    , done: function (page, next) { //到达临界点（默认滚动触发），触发下一页
                        var lis = [];
                        //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                        $.get('/home/sendsign/gettemplist?title='+title+'&page=' + page, function (res) {
                            if(res.code ==2){
                                window.location.href='/home/index/index';
                            }
                            //假设你的列表返回在data集合中
                            layui.each(res.data, function (index, item) {
                                lis.push('<li>' +
                                    '        <a href="/home/sendsign/tempinfo/id/'+item.id+'/imgid/'+imgid+'/htid/'+htid+'">'+item.name+'</a>' +
                                    '    </li>');
                            });
                            next(lis.join(''), page < res.count);
                        }, 'json');
                    }
                });
                //$("#test"+pid).html('');
                // $("#test"+pid).remove();
                // $("#rq").append(' <ul class="tax-documents-list  tax-documents-list-cur son-t" id="test'+pid+'" data-key="<?php echo ($key); ?>"></ul>');

            }else{
                flow.load({
                    elem: '#test' //指定列表容器
                    , scrollElem: '.template-list'
                    , isAuto: true
                    , end: '<span style="color:#07aefc;font-size: 14px;">已经到底</span>'
                    , done: function (page, next) { //到达临界点（默认滚动触发），触发下一页
                        var lis = [];
                        //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                        $.get('/home/sendsign/gettemplist?page=' + page, function (res) {
                            if(res.code ==2){
                                window.location.href='/home/index/index';
                            }
                            //假设你的列表返回在data集合中
                            layui.each(res.data, function (index, item) {
                                lis.push('<li>' +
                                    '        <a href="/home/sendsign/tempinfo/id/'+item.id+'/imgid/'+imgid+'/htid/'+htid+'">'+item.name+'</a>' +
                                    '    </li>');
                            });
                            next(lis.join(''), page < res.count);
                        }, 'json');
                    }
                });
            }

        });
    }
</script>
</body>
</html>