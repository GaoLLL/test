<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1">
    <title>上传签署文件</title>
    <link type="text/css" rel="stylesheet" href="/files/css/main.css">
    <link rel="stylesheet" type="text/css" href="/files/webupload/webuploader.css">
	<link type="text/css" rel="stylesheet" href="/files/swiper/css/swiper.min.css">
    <script language="javascript" type="text/javascript" src="/files/js/jquery-3.3.1.js"></script>
    <script language="javascript" type="text/javascript" src="/files/js/main.js"></script>
	<script language="javascript" type="text/javascript" src="/files/swiper/js/swiper.min.js"></script>
    <script type="text/javascript" src="/files/webupload/webuploader.js"></script>
    <script language="javascript" type="text/javascript" src="/files/layer_mobile/layer.js"></script>
    <script language="javascript" type="text/javascript" src="/Public/exif/exif.js"></script>
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
   <input type="hidden" value="" id="bs"/>
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
<div class="swiper-container view-img-list">
	<div class="swiper-pagination"></div>
    <div class="swiper-wrapper">
    </div>
	<a href="javascript:void(0)" class="view-close" onClick="viewClose()">
		<img src="/files/images/close.png">
	</a>
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
            pick: {
                id:'#filePicker',
                multiple:false,
            },

            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
        uploader.on( 'fileQueued', function( file ) {

            var $li = $(
                '<div id="' + file.id + '" class="file-item thumbnail view-img" onClick="viewImg()">' +
                '<img>' +
                '</div>'
                ),
                $img = $li.find('img');
            $("#nownode").val(file.id);

            // $list为容器jQuery实例
            $("#fileList").append( $li );
            $("#bs").val(file.id);
            // 创建缩略图
            // 如果为非图片文件，可以不用调用此方法。
            // thumbnailWidth x thumbnailHeight 为 100 x 100
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }
              var index =   layer.open({
                     type: 2
                    ,content: '加载中'
                });
                //$img.attr( 'src', src );
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
            console.log()
            console.log(response);
        });
        var fileCount;
        var fileSize;
        var addFile;
        uploader.onFileQueued = function( file ) {

            fileCount++;
            fileSize += file.size;

            if ( fileCount === 1 ) {
                $placeHolder.addClass( 'element-invisible' );
                $statusBar.show();
            }
            var Orientation = 0;
            var fileExif = file.source.source;
            var fileName = fileExif.name;
            var newFile = null;
            //图片方向角
            var rFilter = /^(image\/jpeg|image\/png|image\/jpg|image\/gif|image\/jpe)$/i; // 检查图片格式
            if (rFilter.test(file.type) && file.source.source !== undefined) {
                console.log("旋转开始");
                EXIF.getData(file.source.source, function() {
                    Orientation = EXIF.getTag(this, 'Orientation');
                    if (fileExif && Orientation > 1) {
                        //获取照片方向角属性，用户旋转控制
                        console.log(Orientation);
                        var oReader = new FileReader();
                        oReader.readAsDataURL(fileExif);

                        oReader.onload = function(e) {
                            var image = new Image();
                            image.src = e.target.result;
                            image.onload = function() {
                                var expectWidth = this.naturalWidth;
                                var expectHeight = this.naturalHeight;

                                var canvas = document.createElement("canvas");
                                var ctx = canvas.getContext("2d");
                                canvas.width = expectWidth;
                                canvas.height = expectHeight;
                                ctx.drawImage(this, 0, 0, expectWidth, expectHeight);
                                var base64 = null;
                                //修复ios
                                if (navigator.userAgent.match(/iphone/i)) {
                                    console.log('iphone');
                                    if(Orientation != "" && Orientation != 1){
                                        switch(Orientation){
                                            case 6:
                                                rotateImg(this,'left',canvas);
                                                break;
                                            case 8:
                                                rotateImg(this,'right',canvas);
                                                break;
                                            case 3:
                                                rotateImg(this,'right',canvas);//转两次
                                                rotateImg(this,'right',canvas);
                                                break;
                                        }
                                    }
                                    base64 = canvas.toDataURL(fileExif.type, 1);
                                }else if (navigator.userAgent.match(/Android/i)) {
                                    var encoder = new JPEGEncoder();
                                    base64 = encoder.encode(ctx.getImageData(0, 0, expectWidth, expectHeight), 80);
                                }else{
                                    if(Orientation != "" && Orientation != 1){
                                        switch(Orientation){
                                            case 6:
                                                rotateImg(this,'left',canvas);
                                                break;
                                            case 8:
                                                rotateImg(this,'right',canvas);
                                                break;
                                            case 3:
                                                rotateImg(this,'right',canvas);//转两次
                                                rotateImg(this,'right',canvas);
                                                break;
                                        }
                                    }
                                    base64 = canvas.toDataURL(fileExif.type, 1);
                                }
                                var baseFile = dataURLtoFile(base64, fileName);
                                newFile = baseFile;
                                file.source.source = newFile;
                                addFile(file);
                                setState('ready');
                                updateTotalProgress();
                            };
                        };
                    }else {
                        addFile( file );
                        setState( 'ready' );
                        updateTotalProgress();
                    }
                });
            } else {
                addFile( file );
                setState( 'ready' );
                updateTotalProgress();
            }

            function dataURLtoFile(dataurl, filename) { //将base64转换为文件
                var arr = dataurl.split(','),
                    mime = arr[0].match(/:(.*?);/)[1],
                    bstr = atob(arr[1]),
                    n = bstr.length,
                    u8arr = new Uint8Array(n);
                while (n--) {
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new File([u8arr], filename,{
                    type: mime
                });
            }

            function rotateImg(img, direction,canvas) {
                //alert(img);
                //最小与最大旋转方向，图片旋转4次后回到原方向
                var min_step = 0;
                var max_step = 3;
                //var img = document.getElementById(pid);
                if (img == null)return;
                //img的高度和宽度不能在img元素隐藏后获取，否则会出错
                var height = img.height;
                var width = img.width;
                //var step = img.getAttribute('step');
                var step = 2;
                if (step == null) {
                    step = min_step;
                }
                if (direction == 'right') {
                    step++;
                    //旋转到原位置，即超过最大值
                    step > max_step && (step = min_step);
                } else {
                    step--;
                    step < min_step && (step = max_step);
                }
                //旋转角度以弧度值为参数
                var degree = step * 90 * Math.PI / 180;
                var ctx = canvas.getContext('2d');
                switch (step) {
                    case 0:
                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0);
                        break;
                    case 1:
                        canvas.width = height;
                        canvas.height = width;
                        ctx.rotate(degree);
                        ctx.drawImage(img, 0, -height);
                        break;
                    case 2:
                        canvas.width = width;
                        canvas.height = height;
                        ctx.rotate(degree);
                        ctx.drawImage(img, -width, -height);
                        break;
                    case 3:
                        canvas.width = height;
                        canvas.height = width;
                        ctx.rotate(degree);
                        ctx.drawImage(img, -width, 0);
                        break;
                }
            }

        };


        function delimg(i){
			var	obj = $(".view-img").eq(i);
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
						$(".view-img-list .swiper-wrapper .swiper-slide").remove();
						viewImg();
                    },'json')
                    layer.close(index);
                }
            });
			
        }
		function viewImg(){
			var viewLength = $(".view-img").length;
			if(viewLength>0){
				$(".view-img img").each(function(i){
					var imgSrc = $(".view-img img").eq(i).attr("src");
					$(".view-img-list .swiper-wrapper").append("<div class='swiper-slide'><img src='"+imgSrc+"' class='view-list'><a href='javascript:void(0)' class='view-del' onClick='delimg("+i+")'><img src='/files/images/signature_delete.png'></a></div>");
					$(".view-img-list").css("display","block");
					var swiper = new Swiper('.view-img-list', {
					  pagination: {
						el: '.swiper-pagination',
						type: 'fraction',
					  },
					});
				})
			}else{
				$(".view-img-list").css("display","none");
			}
			
		}
		function viewClose(){
			$(".view-img-list").css("display","none");
			$(".view-img-list .swiper-wrapper .swiper-slide").remove();
		}
    </script>

</body>
</html>