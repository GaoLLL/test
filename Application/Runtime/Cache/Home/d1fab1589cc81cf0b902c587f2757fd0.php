<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>机构设置</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
</head>

<body>
<div class="tax-documents-search2" style="top: 0;">
    <input type="text" placeholder="请输入搜索文件名称" id="search">
    <a href="javascript:void(0)"  onclick="getsignlist(this)" class="tax-documents-search-btn">搜索</a>
</div>
<div class="institutional-hierarchy2" style="top: 11.93vw">
    <p id="topnav">
        <a href="javascript:void(0)" onclick="getsignlist(this)" data-name="<?php echo ($first); ?>" data-status="remove" data-fid="0"><?php echo ($first); ?></a>
    </p>
</div>
<div class="search-height3"></div><!-- 显示 -->
<!--<div class="search-height2"></div>--><!-- 不显示-->

<ul class="institutional-settings" style="overflow: scroll; height: 40vh;" id="signnamelist">
    <?php if(is_array($signnamelist)): $i = 0; $__LIST__ = $signnamelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <a href="javascript:void(0)" class="institutional-settings-a getlist" data-fid="<?php echo ($vo["uid"]); ?>" data-name="<?php echo ($vo["username"]); ?>" data-status="add" onclick="getsignlist(this)"><?php echo ($vo["username"]); ?></a>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<ul class="institutional-man" id="signnamelist-user" style="margin-bottom: 5vw;">
    <?php if(is_array($people)): $i = 0; $__LIST__ = $people;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <a class="background-none" href="/home/sendsign/userinfo/id/<?php echo ($vo["id"]); ?>">
                <img src="<?php echo ($vo["topimage"]); ?>">
                <div class="institutional-man-info2">
                    <p><?php echo ($vo["username"]); ?><m><?php echo ($vo["worker"]); ?></m></p>
                    <span><?php echo ($vo["tel"]); ?></span>
                </div>
            </a>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<script>
    function getsignlist(obj) {
        var fid = $(obj).data('fid');
        var username = $(obj).data('name');
        var status = $(obj).data('status');
        var title = $("#search").val();
        var html = '';
        var html2 = '';
        $.post('/home/sendsign/getuserlist', {fid: fid,title:title}, function (data) {
            $.each(data.data, function (index, item) {
                if(data.isend==1){
                    html += ' <li>' +
                        '       <a class="background-none" href="/home/sendsign/userinfo/id/'+item.uid+'">' +
                        '                 <img src="'+item.topimage+'">'+
                        '                 <div class="institutional-man-info2">' +
                        '                      <p>' + item.username + '<m>'+item.worker+'</m></p>' +
                        '                      <span>' + item.tel + '</span>' +
                        '                 </div>' +
                        '      </a>' +
                        '</li>'
                }else{
                    html += "<li><a href='javascript:void(0)' class='institutional-settings-a getlist' data-fid='" + item.uid + "' data-name='"+item.username+"' data-status='add' onclick='getsignlist(this)'>" + item.username + "</a></li>";
                }

            });

            $.each(data.people, function (index, item) {
                html2 += ' <li>' +
                    '       <a class="background-none" href="/home/sendsign/userinfo/id/'+item.uid+'">' +
                    '                 <img src="'+item.topimage+'">'+
                    '                 <div class="institutional-man-info2">' +
                    '                      <p>' + item.username + '<m>'+item.worker+'</m></p>' +
                    '                      <span>' + item.tel + '</span>' +
                    '                 </div>' +
                    '      </a>' +
                    '</li>'
            })
            if(data.isend==1){
                if(status=='add'){
                    $("#topnav").prepend('<a href="javascript:void(0)" style="color:#333333;" data-fid="'+fid+'" data-name="'+username+'" data-status="remove">'+username+'</a>');
                }else{
                   $("#topnav").find('a').each(function(ii,vv){
                        if($(this).data('fid') > fid){
                            $(this).remove();
                        }
                    })
                }
                $("#signnamelist").html('');
                $("#signnamelist").css('height',0);
                $("#signnamelist-user").html('');
                $("#signnamelist-user").append(html);
                $("#signnamelist-user").append(html2);
            }else{
                if(status=='add'){
                    $("#topnav").prepend('<a href="javascript:void(0)" onclick="getsignlist(this)" data-fid="'+fid+'" data-name="'+username+'" data-status="remove">'+username+'</a>');
                }else{
                    $("#topnav").find('a').each(function(ii,vv){
                        if($(this).data('fid') > fid){
                            $(this).remove();
                        }
                    })
                }
                $("#signnamelist").html('');
                $("#signnamelist").css('height','40vh');
                $("#signnamelist").html(html);
                $("#signnamelist-user").html('');
                $("#signnamelist-user").html(html2);
            }
        }, 'json')

    }
</script>
<div class="net-sign-footer-height"></div>
<ul class="net-sign-footer">
    <li>
        <a href="<?php echo U('Sendsign/index');?>">
            <img src="/files/images/net-sign-footer-icon-01.png">
            <span>发起审批</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Sendsign/alllist');?>">
            <img src="/files/images/net-sign-footer-icon-03.png">
            <span>签署文件</span>
        </a>
    </li>
    <li>
        <a href="<?php echo U('Sendsign/userlist');?>" class="net-sign-footer-cur">
            <img src="/files/images/net-sign-footer-icon-04-a.png">
            <span>机构设置</span>
        </a>
    </li>
</ul>
</body>
</html>