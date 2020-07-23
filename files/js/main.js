// JavaScript Document
$(function(){
	style();
	$(".institutional-settings-a").each(function(i){
		$(".institutional-settings-a").eq(i).click(function(){
			$(".institutional-settings-a").eq(i).toggleClass("institutional-settings-cur");
			$(".institutional-settings-list").eq(i).toggleClass("institutional-settings-list-cur");
		})
	})
	$(".login-choose a").each(function(i){
		$(".login-choose a").eq(i).click(function(){
			$(".login-choose a").removeClass("login-choose-cur");
			$(".login-choose a").eq(i).addClass("login-choose-cur");
			$(".login-number").val(i);
		})
	})
    /*$(".signature-class a").each(function(i){
        console.log(1);
        $(".signature-class a").eq(i).on('click',function(){
            console.log(2);
            $(".signature-class a").removeClass("signature-class-cur");
            $(".signature-class a").eq(i).addClass("signature-class-cur");
            $(".sign-seal").removeClass("sign-seal-cur");
            $(".sign-seal").eq(i).addClass("sign-seal-cur");
        })
    })*/
	$(".login-out").click(function(){
		$(".spring-window").css("display","block");
	})
	$(".sign-initiate-sure").click(function(){
		$(".spring-window").css("display","block");
	})
	$(".sign-refuse").click(function(){
		$(".win-refuse").css("display","block");
	})
	$(".sign-accept").click(function(){
		$(".win-accept").css("display","block");
	})
	$(".win-btn-cancle").click(function(){
		$(".spring-window").css("display","none");
	})
	$(".circulation a").each(function(i){
		$(".circulation a").eq(i).click(function(){
			//console.log($(this).data('id'));
			$('#nowposttype').val($(this).data('id'));
			$(".circulation a").removeClass("circulation-cur");
			$(".circulation a").eq(i).addClass("circulation-cur");
			$(".signing-choose").removeClass("signing-choose-cur");
			$(".signing-choose").eq(i).addClass("signing-choose-cur");
		})
	})
    $(".pinch-img").each(function(i){
        var src = $(".pinch-img").eq(i).attr("src");
        $(".sign-ccanvas-img").eq(i).attr("src",src);
    })
	$(".stamp a").each(function(i){
		$(".stamp a").eq(i).click(function(){
			$(".stamp a").removeClass("stamp-cur");
			$(".stamp a").eq(i).addClass("stamp-cur");
			if(i==0){
				$(".signing-list-b").css("display","block");
			}else{
				$(".signing-list-b").css("display","none");
			}
		})
	})
	$(".seal-list2 li").each(function(i){
		$(".seal-list2 li").eq(i).click(function(){
			$(".seal-list2 li").removeClass("seal-list2-cur");
			$(".seal-list2 li").eq(i).addClass("seal-list2-cur");
		})
	})
	$(".signature-choose").each(function(i){
		console.log(i);
		$(".signature-choose").eq(i).click(function(){
			$(".signature-choose").removeClass("signature-choose-cur");
			$(".signature-choose").eq(i).addClass("signature-choose-cur");
		})
	})
    $(".immediately-img2").click(function(){
        var daLength = $(".sign-now-img").length;
        if(daLength>0){
            var Length2 = $(".swiper-slide-active .date-img").length;
            if(Length2>=1){
                return;
            }else{
                var s;
                $(".swiper-slide").each(function(j){
                    if($(".swiper-slide").eq(j).hasClass("swiper-slide-active")){
                        s = j;
                    }
                })
                var Img2 = $(".immediately-img2").find("img").attr("src");
                $(".swiper-slide-active").append("<div class='date-img draggable' id='dateCSS"+s+"'><p><img src='"+Img2+"' class='date-imgs handle' id='dateImgCSS"+s+"'></p><a href='javascript:void(0)' class='date-img-close' onClick='dateImgClose("+s+")'></a><div class='date-scal-zone'><a href='javascript:void(0)'' class='date-img-scall'></a></div></div>");
                var $draggables = $('.draggable').draggabilly({
                    handle: '.handle',
                    containment: true,
                });
                var winWidth = document.documentElement.offsetWidth;
                var demo = document.querySelector('.swiper-slide-active .date-img .date-scal-zone');
                var elem = demo.querySelector('.date-img-scall');
                var draggie = new Draggabilly( elem, {
                    containment: true,
                    axis: 'x'
                });
                draggie.on( 'dragMove', function() {
                    var position = this.position;
                    var newWidth = winWidth*0.09+position.x;
                    $(".swiper-slide-active .date-img p").css("width",newWidth);
                });
            }
        }else{
            return;
        }
    })
    $(".immediately-img").each(function(i){
        $(".immediately-img").eq(i).click(function(){
            var Img = $(".immediately-img").eq(i).find("img").attr("src");
            var Length = $(".swiper-slide-active .sign-now-img").length;
            var s;
            $(".swiper-slide").each(function(j){
                if($(".swiper-slide").eq(j).hasClass("swiper-slide-active")){
                    s = j;
                }
            })
            if(Length>=1){
                return;
            }else{
                $(".swiper-slide-active").append("<div class='sign-now-img draggable' id='signCSS"+s+"'><p><img src='"+Img+"' class='sign-imgs handle' id='imgCSS"+s+"'></p><a href='javascript:void(0)' class='sign-img-close' onClick='signImgClose("+s+")'></a><div class='sign-scal-zone'><a href='javascript:void(0)'' class='sign-img-scall'></a></div></div>");
                var $draggables = $('.draggable').draggabilly({
                    handle: '.handle',
                    containment: true,
                });
                var winWidth = document.documentElement.offsetWidth;
                var demo = document.querySelector('.swiper-slide-active .sign-now-img .sign-scal-zone');
                var elem = demo.querySelector('.sign-img-scall');
                var draggie = new Draggabilly( elem, {
                    containment: true,
                    axis: 'x'
                });
                draggie.on( 'dragMove', function() {
                    var position = this.position;
                    var newWidth = winWidth*0.09+position.x;
                    $(".swiper-slide-active .sign-now-img p").css("width",newWidth);
                });
            }
        })
    })
});


function signImgClose(i){
    $(".swiper-slide").eq(i).find(".sign-now-img").remove();
   // var src = $(".pinch-img").eq(i).attr("src");
   // $(".sign-ccanvas-img").eq(i).attr("src",src);
}
function dateImgClose(i){
    $(".swiper-slide").eq(i).find(".date-img").remove();
}

function taxClassCur(){
	$(".tax-documents-class .swiper-slide").each(function(i){
		var taxClassCur = $(".tax-documents-class .swiper-slide").eq(i).hasClass("swiper-slide-active");
		if(taxClassCur){
			$(".tax-documents-class-num").val(i);
		}else{
			return;
		}
	})
}
function taxList(){
	var taxClassNum = $(".tax-documents-class-num").val();
	var taxClassNum2 = $(".tax-documents-class-num2").val();
	if(taxClassNum==taxClassNum2){
		return;
	}else{
		$(".tax-documents-list").removeClass("tax-documents-list-cur");
		$(".tax-documents-list").eq(taxClassNum).addClass("tax-documents-list-cur");
		$(".tax-documents-class-num2").val(taxClassNum);
	}
}
function style(){
	var w = document.documentElement.offsetWidth;
	var h = document.documentElement.clientHeight;
	var signHeight = h*0.4;
	$(".canvasDiv").css("width",w);
	$("#canvasEdit").css("height",signHeight);
	$("#canvasEdit").css("width",w);
	var sealListHeight = h-0.2882*w;
	$(".seal-list2").css("height",sealListHeight);
	$(".sign-seal").css("height",h);
}

setInterval(taxClassCur,1);
setInterval(taxList,1);
function draw() {
    $(".swiper-slide").each(function(i){
        var winWidth = document.documentElement.offsetWidth;
        var ss = $(".swiper-slide").eq(i).find(".sign-now-img").length;
        var tt = $(".swiper-slide").eq(i).find(".date-img").length;
        var id = "signCSS"+i;
        var id2 = "imgCSS"+i;
        var id3 = "dateCSS"+i;
        var id4 = "dateImgCSS"+i;
        if(tt>0){
            if(ss>0){
                var base64=[];
                var src1 = $(".swiper-slide").eq(i).find('.pinch-img').attr('src');
                var src2 = $(".swiper-slide").eq(i).find('.sign-imgs').attr('src');
                var src3 = $(".swiper-slide").eq(i).find('.date-imgs').attr('src');
                var seal = document.getElementById(id);
                var seal2 = document.getElementById(id2);
                var seal3 = document.getElementById(id3);
                var seal4 = document.getElementById(id4);
                var src2Width = seal2.offsetWidth;
                var src2Height = seal2.offsetHeight;
                var src2Left = seal.offsetLeft+0.0688*winWidth;
                var src2Top = seal.offsetTop+0.0688*winWidth;
                var src4Width = seal4.offsetWidth;
                var src4Height = seal4.offsetHeight;
                var src4Left = seal3.offsetLeft+0.0688*winWidth;
                var src4Top = seal3.offsetTop+0.0688*winWidth;
                var data = [src1, src2,src3];//图片数组
                var c = document.createElement('canvas'),
                    ctx = c.getContext('2d');
                c.width = 0.8594*winWidth;
                c.height = 1.2031*winWidth;
                ctx.rect(0, 0, c.width, c.height);
                //						ctx.fillStyle = '#fff';
                ctx.fill();
                var img = new Image;

                img.src = data[0];
                ctx.drawImage(img, 0, 0, c.width, c.height);//定位图片位置及大小
                img.src=data[1];
                ctx.drawImage(img, src2Left, src2Top, src2Width, src2Height);
                img.src=data[2];
                ctx.drawImage(img, src4Left, src4Top, src4Width, src4Height);
                base64.push(c.toDataURL("image/jpeg", 1));
                $(".sign-ccanvas-img").eq(i).attr("src",base64[0]);
            }else{
                var base64=[];
                var src3 = $(".swiper-slide").eq(i).find('.pinch-img').attr('src');
                var src4 = $(".swiper-slide").eq(i).find('.date-imgs').attr('src');
                var seal3 = document.getElementById(id3);
                var seal4 = document.getElementById(id4);
                var src4Width = seal4.offsetWidth;
                var src4Height = seal4.offsetHeight;
                var src4Left = seal3.offsetLeft+0.0688*winWidth;
                var src4Top = seal3.offsetTop+0.0688*winWidth;
                var data = [src3, src4];//图片数组
                var c = document.createElement('canvas'),
                    ctx = c.getContext('2d');
                c.width = 0.8594*winWidth;
                c.height = 1.2031*winWidth;
                ctx.rect(0, 0, c.width, c.height);
                //						ctx.fillStyle = '#fff';
                ctx.fill();
                var img = new Image;

                img.src = data[0];
                ctx.drawImage(img, 0, 0, c.width, c.height);//定位图片位置及大小
                img.src=data[1];
                ctx.drawImage(img, src4Left, src4Top, src4Width, src4Height);
                base64.push(c.toDataURL("image/jpeg", 1));
                //						fn();
                //					console.log(base64)

                //					return base64;//base64[0]即为图片的src
                $(".sign-ccanvas-img").eq(i).attr("src",base64[0]);
            }
        }else{
            if(ss>0){
                var base64=[];
                var src1 = $(".swiper-slide").eq(i).find('.pinch-img').attr('src');
                var src2 = $(".swiper-slide").eq(i).find('.sign-imgs').attr('src');
                var seal = document.getElementById(id);
                var seal2 = document.getElementById(id2);
                var src2Width = seal2.offsetWidth;
                var src2Height = seal2.offsetHeight;
                var src2Left = seal.offsetLeft+0.0688*winWidth;
                var src2Top = seal.offsetTop+0.0688*winWidth;
                var data = [src1, src2];//图片数组
                var c = document.createElement('canvas'),
                    ctx = c.getContext('2d');
                c.width = 0.8594*winWidth;
                c.height = 1.2031*winWidth;
                ctx.rect(0, 0, c.width, c.height);
                //						ctx.fillStyle = '#fff';
                ctx.fill();
                var img = new Image;

                img.src = data[0];
                ctx.drawImage(img, 0, 0, c.width, c.height);//定位图片位置及大小
                img.src=data[1];
                ctx.drawImage(img, src2Left, src2Top, src2Width, src2Height);
                base64.push(c.toDataURL("image/jpeg", 1));
                $(".sign-ccanvas-img").eq(i).attr("src",base64[0]);
            }else{
                return;
            }
        }

    })
}