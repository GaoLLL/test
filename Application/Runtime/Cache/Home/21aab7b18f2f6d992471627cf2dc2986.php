<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>税务文书</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <link type="text/css" rel="stylesheet" href="/files/swiper/css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="/Public/layui/css/layui.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="/files/swiper/js/swiper.min.js"></script>
    <script src="/Public/layui/layui.js" type="text/javascript"></script>
</head>

<body>
<div class="swiper-container tax-documents-banner">
    <div class="swiper-wrapper tax-documents-class">
        <?php if(is_array($toplist)): $i = 0; $__LIST__ = $toplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vi): $mod = ($i % 2 );++$i;?><div class="swiper-slide" data-pid="<?php echo ($vi["id"]); ?>"><?php echo ($vi["name"]); ?></div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<input type="hidden" value="0" class="tax-documents-class-num">
<input type="hidden" value="0" class="tax-documents-class-num2">
<input type="hidden" value="<?php echo ($nowpid); ?>" id="nowpid"/>
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 5,
        spaceBetween: 30,
        centeredSlides: true,
        on: {
            slideChangeTransitionEnd: function(){
                var nowindex = this.activeIndex;
                var nowpids  = $('.swiper-wrapper').find('div').eq(nowindex).data('pid');
                $('#nowpid').val(nowpids);
                oneshow();
                console.log(nowpids);
            },
        }
    });
</script>
<div class="tax-documents-search2">
    <input type="text" placeholder="请输入搜索文件名称" id="search">
    <a href="javascript:void(0);" class="tax-documents-search-btn" onclick="oneshow()">搜索</a>
</div>
<div class="search-height2"></div>
<div id="rq">


<?php if(is_array($toplist)): $key = 0; $__LIST__ = $toplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><ul class="tax-documents-list <?php if(($key) == "1"): ?>tax-documents-list-cur<?php endif; ?>  son-t" id="test<?php echo ($vo["id"]); ?>" data-key="<?php echo ($key); ?>">

    </ul><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<!--<ul class="tax-documents-list son-t">
    <li>
        <a href="tax_documents_detail.html">税务政策文书标题名称</a>
    </li>
    <li>
        <a href="tax_documents_detail.html">税务政策文书标题名称</a>
    </li>
    <li>
        <a href="tax_documents_detail.html">税务政策文书标题名称</a>
    </li>
</ul>
<ul class="tax-documents-list">
    <li>
        <a href="tax_documents_detail.html">税务政策文书标题名称</a>
    </li>
    <li>
        <a href="tax_documents_detail.html">税务政策文书标题名称</a>
    </li>
</ul>-->

</body>

<script>
    $(function(){
        setTimeout(function(){
            oneshow();
        },500)
    })
    function oneshow(){
        var pid = $("#nowpid").val();
        var title = $("#search").val();
        /*if(title !=''){
            layui.flow.load({
                elem: "#test"+pid
            });
        }*/
        layui.use('flow', function () {
            var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
            var flow = layui.flow;
            $("#test"+pid).html('');
            if(title !=''){
                flow.load({
                    elem: '#test'+pid //指定列表容器
                    , scrollElem: '.son-t'
                    , isAuto: true
                    , end: '<span style="color:#07aefc;font-size: 14px;">已经到底</span>'
                    , done: function (page, next) { //到达临界点（默认滚动触发），触发下一页
                        var lis = [];
                        //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                        $.get('/home/netsign/getformlist?pid='+pid+'&title='+title+'&page=' + page, function (res) {
                            if(res.code ==2){
                                window.location.href='/home/index/index';
                            }
                            //假设你的列表返回在data集合中
                            layui.each(res.bottomlist, function (index, item) {
                                lis.push('<li>' +
                                    '        <a href="/home/netsign/forminfo/id/'+item.id+'">'+item.title+'</a>' +
                                    '    </li>');
                            });
                            next(lis.join(''), page < res.pages);
                        }, 'json');
                    }
                });
                //$("#test"+pid).html('');
               // $("#test"+pid).remove();
               // $("#rq").append(' <ul class="tax-documents-list  tax-documents-list-cur son-t" id="test'+pid+'" data-key="<?php echo ($key); ?>"></ul>');

            }else{
                flow.load({
                    elem: '#test'+pid //指定列表容器
                    , scrollElem: '.son-t'
                    , isAuto: true
                    , end: '<span style="color:#07aefc;font-size: 14px;">已经到底</span>'
                    , done: function (page, next) { //到达临界点（默认滚动触发），触发下一页
                        var lis = [];
                        //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                        $.get('/home/netsign/getformlist?pid='+pid+'&page=' + page, function (res) {
                            if(res.code ==2){
                                window.location.href='/home/index/index';
                            }
                            //假设你的列表返回在data集合中
                            layui.each(res.bottomlist, function (index, item) {
                                lis.push('<li>' +
                                    '        <a href="/home/netsign/forminfo/id/'+item.id+'">'+item.title+'</a>' +
                                    '    </li>');
                            });
                            next(lis.join(''), page < res.pages);
                        }, 'json');
                    }
                });
            }

        });
    }
</script>

<script>
    function yhdList(orgcode, year) {
        var pid = $("#nowpid").val();
        layui.flow.load({
            elem: "#test"+pid
        });
    }
</script>

</html>