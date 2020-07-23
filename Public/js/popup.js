/**
 * 
 */
(function($){
    $.popup = {
    	_op:{width:500,height:400},

    	open: function(options){
    		var op = $.extend({}, $.popup._op, options);
            var popContent = $("."+op.obj);

    		var top,left,maskWidth,maskHeight,winHeight;
            
            maskWidth = $(document).width();
            maskHeight = $(document).height();
            winHeight = $(window).height();

            var maskHtml = "<div class='mask' style='width:"+maskWidth+"px;height:"+maskHeight+"px;'></div>";

            $("body").append(maskHtml);

            if(maskHeight < popContent.height()){
                popContent.find(".popup-bd").css("height",maskHeight-142);
            }

            left = (maskWidth - op.width) / 2;
            top = (winHeight- popContent.height()+ $(document).scrollTop())/2 ;

            popContent.css({
                "top":top,
                "left":left,
                "width":op.width
            }).show();

            $(".popup-close").live("click",function(){
                $.popup.close(options);
            });
    	},

    	close:function(options){
            var op = $.extend({}, $.popup._op, options);
            var popContent = $("."+op.obj);
            popContent.hide();
            $(".mask").remove();
    	}
    };
})(jQuery);