<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>签署文件</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <link rel="stylesheet" type="text/css" href="/Public/layui/css/layui.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script src="/Public/layui/layui.js" type="text/javascript"></script>
</head>

<body>
<div class="sign-document-class">
    <a href="<?php echo U('sendsign/alllist');?>" class="sign-document-class-cur" style="margin: 0 16%;width: 18%;">已受理</a>
    <a href="<?php echo U('sendsign/mysignlist');?>" style="margin: 0 16%;width: 18%;">未受理</a>
</div>
<ul class="sign-document-list" id="rq">
    <!--<li>
        <a href="sign_document_detail_initiate.html">
            <div class="sign-document-time">9:00</div>
            <div class="sign-document-cont">
                <p class="sign-document-title">MAD项目合同协议书</p>
                <p class="sign-document-tips">
                    <span class="sign-complete">已完成</span>
                    <span class="sign-my">我发起</span>
                </p>
                <p class="sign-document-date">
                    <span>发起时间：2018.05.13 15:26:46</span>
                    <span>结束时间：2018.05.14</span>
                </p>
            </div>
        </a>
    </li>
    <li>
        <a href="sign_document_detail_initiate.html">
            <div class="sign-document-time">9:00</div>
            <div class="sign-document-cont">
                <p class="sign-document-title">MAD项目合同协议书</p>
                <p class="sign-document-tips">
                    <span class="sign-progress">签署中</span>
                    <span class="sign-my">我签署</span>
                </p>
                <p class="sign-document-date">
                    <span>发起时间：2018.05.13 15:26:46</span>
                    <span>结束时间：2018.05.14</span>
                </p>
            </div>
        </a>
    </li>
    <li>
        <a href="sign_document_detail_initiate.html">
            <div class="sign-document-time">9:00</div>
            <div class="sign-document-cont">
                <p class="sign-document-title">MAD项目合同协议书</p>
                <p class="sign-document-tips">
                    <span class="sign-refusal">已拒签</span>
                    <span class="sign-my">我签署</span>
                </p>
                <p class="sign-document-date">
                    <span>发起时间：2018.05.13 15:26:46</span>
                    <span>结束时间：2018.05.14</span>
                </p>
            </div>
        </a>
        <p class="refusal-reason">拒签理由：理由理由理由理由理由理由理由理由理由理由</p>
    </li>-->
</ul>
<div class="net-sign-footer-height"></div>
<ul class="net-sign-footer">
    <li>
        <a href="<?php echo U('Sendsign/index');?>">
            <img src="/files/images/net-sign-footer-icon-01.png">
            <span>发起审批</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Sendsign/alllist');?>" class="net-sign-footer-cur">
            <img src="/files/images/net-sign-footer-icon-02-a.png">
            <span>签署文件</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Sendsign/userlist');?>">
            <img src="/files/images/net-sign-footer-icon-04.png">
            <span>机构设置</span>
        </a>
    </li>
</ul>
<script>
    $(function(){
        setTimeout(function(){
            oneshow();
        },500)
    })
    function oneshow(){
        layui.use('flow', function () {
            var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
            var flow = layui.flow;
            $("#rq").html('');
            flow.load({
                elem: '#rq' //指定列表容器
                , scrollElem: '#rq'
                , isAuto: true
                , end: '<span style="color:#07aefc;font-size: 14px;">已经到底</span>'
                , done: function (page, next) { //到达临界点（默认滚动触发），触发下一页
                    var lis = [];
                    //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                    $.get('/home/sendsign/getzrralllist?page=' + page+'&type=1', function (res) {
                        if(res.code ==2){
                            window.location.href='/home/index/index';
                        }
                        //假设你的列表返回在data集合中
                        layui.each(res.data, function (index, item) {
                            if(item.status == 0){
                                var statusname = '<span class="sign-progress">进行中</span>';
                            }else if(item.status == 1){
                                var statusname = '<span class="sign-complete">已同意</span>';
                            }else if(item.status == 2){
                                var statusname = '<span class="sign-refusal">已拒绝</span>';
                            }
                            lis.push('<li>' +
                                '        <a  href="javascript:void(0)">' +
                                '            <div class="sign-document-time">'+item.lefttime+'</div>' +
                                '            <div class="sign-document-cont">' +
                                '                <p class="sign-document-title">'+item.title+'</p>' +
                                '                <p class="sign-document-tips">' +
                                statusname +
                                '                </p>' +
								' 				<p class="sign-document-date">'+
								'					<span>&nbsp;</span>'+
								'					<span>&nbsp;</span>'+
								'				</p>'+
                                '            </div>' +
                                '        </a>' +
                                '    </li>');
                        });
                        next(lis.join(''), page < res.pages);
                    }, 'json');
                }
            });
            //$("#test"+pid).html('');
            // $("#test"+pid).remove();
            // $("#rq").append(' <ul class="tax-documents-list  tax-documents-list-cur son-t" id="test'+pid+'" data-key="<?php echo ($key); ?>"></ul>');



        });
    }
</script>
</body>
</html>