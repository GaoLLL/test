<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>上传签署文件</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <link rel="stylesheet" type="text/css" href="/files/webupload/webuploader.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
    <script type="text/javascript" src="/files/webupload/webuploader.js"></script>
    <script language="javascript" type="text/javascript" src="/files/layer_mobile/layer.js"></script>
</head>

<body>
<!-- <div class="z_container">
     <div class="z_photo">
         <m class="z_input">

         </m>
         <div class="z_file">
             <span>继续添加</span>
             <input type="file" name="file" id="file" value="" accept="image/*" multiple="" onchange="imgChange(&#39;z_input&#39;,&#39;z_file&#39;);">
         </div>
     </div>
 </div>-->
<input type="hidden" value="" id="nowarr"/>
<input type="hidden" value="" id="nownode"/>
<div id="uploader-demo" class="z_file">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <span id="filePicker">选择图片</span>
</div>

<a href="javascript:void(0);" class="sign-accept">发起</a>
<div class="spring-window">
    <div class="win-box">
        <p>确定要删除这张图片吗？</p>
        <span>
				<a href="javascript:void(0)" class="win-btn-cancle z_cancel">取消</a>
				<a href="javascript:void(0)" class="win-btn-sure z_sure">确定</a>
			</span>
    </div>
</div>
<script>
    $(".sign-accept").click(function(){
        if($("#nowarr").val() == ''){
            layer.open({
                content: '添加照片后才能发起签署'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            window.location.href='/home/sendsign/signdo/id/'+$("#nowarr").val();
        }
        //
    })
    var uploader = WebUploader.create({

        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        swf:'/files/webupload/Uploader.swf',

        // 文件接收服务端。
        server: '/home/sendsign/upload',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }
    });
    uploader.on( 'fileQueued', function( file ) {
        var $li = $(
            '<div id="' + file.id + '" class="file-item thumbnail" onclick="delimg(this)">' +
            '<img>' +
            '</div>'
            ),
            $img = $li.find('img');
        $("#nownode").val(file.id);

        // $list为容器jQuery实例
        $("#fileList").append( $li );

        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        uploader.makeThumb( file, function( error, src ) {
            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        }, 100, 100 );
    });
    uploader.on( 'uploadSuccess', function( file,response) {
        var nowimg = $("#nowarr").val();
        if(nowimg == ''){
            var nowval = response.id;
        }else{
            var nowval = nowimg+','+response.id;
        }

        $("#nowarr").val(nowval);
        var nownode = $("#nownode").val();
        $("#"+nownode).attr("data-id",response.id);
        var now = $("#bs").val();
        $('#'+now).find('img').attr( 'src', response.data );
        layer.closeAll();

       /* $("#nowarr").val(nowval);
        var nownode = $("#nownode").val();
        $("#"+nownode).attr("data-id",response.id);
        console.log(response);*/
    });

    function delimg(obj){
        var id = $(obj).data('id');
        layer.open({
            content: '确定要删除当前图片吗？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.post('/home/sendsign/dellinshi',{id:id},function(){

                    var nowarr = $("#nowarr").val();
                    var arr = nowarr.split(",");
                    arr.splice($.inArray(id,arr),1);
                    if(arr.length == 0){
                        $("#nowarr").val('');
                    }else{
                        var str = arr.join(',');
                        $("#nowarr").val(str);
                    }
                    $(obj).remove();
                },'json')
                layer.close(index);
            }
        });
    }
</script>

</body>
</html>