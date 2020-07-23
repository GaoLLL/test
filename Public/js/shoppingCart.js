
(function($){
    $.ajaxSetup({ cache: false });
    function Cart(el,options){
        if(Cart._instance){
            return Cart._instance
        }
        Cart._instance = this;
        this.$el = $(el);
        // 数量
        this.count = 0;
        // 价格
        this.total = 0;
        // data from server
        this.db = {};

        this.init(options);

        this.table_layout = this.$el.find('table').length > 0;
       
        	 this.dl_layout = this.$el.find('dl').length > 0;
        
    }
    var hosts = 'http://www.xuxian.com';
    if (window.location.host == 'zhida.xuxian.com') {
        hosts = 'http://zhida.xuxian.com';
        //alert(hosts);
    }
    $.extend(Cart,{
        defaultOptions : {
            boxSel : '.cart-box',
            dropSel : '.cart-drop',
            countSel : '.item-count em',
            totalsSel : '.totals-price em',
            boxActiveClass : 'cart-box-show',
            listSel : '.cart-items-list'
        },
        APIs : {
            GET_PRODUCTS : hosts + '/index.php?controller=simple&action=getProducts',
            JOIN_CART : hosts + '/index.php?controller=simple&action=joinCart',
            SHOW_CART : hosts + '/index.php?controller=simple&action=showCart&mode=json',
            REMOVE_CART : hosts + '/index.php?controller=simple&action=removeCart'
        }
    });
    $.extend(Cart.prototype,{
        init : function(options){
            var $el = this.$el;
            var opts = this.options = $.extend({},Cart.defaultOptions,options);
            // 上面的盒子
            this.$box = $el.find(opts.boxSel);
            // 下面的商品列表
            this.$drop = $el.find(opts.dropSel);
            this.$goodList = this.$el.find(opts.listSel);
            // 数量
            this.$count = $el.find(opts.countSel);
            // 价格
            this.$total = $el.find(opts.totalsSel);
            // 绑定事件
            this.bindEvents();
        },
        bindEvents : function(){
            var me = this;
            // box event
            //=============================================================
            me.$box.toggle(function(){
                var viewportH = $(window).height();
                var cartH = viewportH - 328;
                $(this).addClass(me.options.boxActiveClass);
				$(".buy").removeClass("btn-cart").addClass("btn-cart2");
                me.$drop.show(400).find('.warehouse-cart').height(cartH);
				$('.cart-items-list').height("auto")
				$(".cart-items-list li").show();
                if(!me._cartRender){
                    me.showCart();
                    me._cartRender = true;
                }
            },function(){
                $(this).removeClass(me.options.boxActiveClass);
                me.$drop.hide(400).find('.warehouse-cart').height("80px");
				$(".buy").removeClass("btn-cart2").addClass("btn-cart");
				
            });

            // goodList event
            //================================================================
            me.$goodList.on('mouseenter','li',function(ev){
                var $li = $(ev.currentTarget);
                $li.addClass('hover');
            }).on('mouseleave','li',function(ev){
                $(ev.currentTarget).removeClass('hover');
            });
            me.$el.on('click','.qty-plus',function(e){
                e.preventDefault();
                var $qty = $(e.currentTarget);
                if(!$qty.hasClass('disabled')){
                    var id= me.getGoodID($qty.parents('.cart-li'));
                    me.addCart(id,1);
                }
            }).on('click','.qty-minus',function(e){
                e.preventDefault();
                var $qty = $(e.currentTarget);
                var id= me.getGoodID($qty.parents('.cart-li'));
                var count = parseInt($qty.next('input').val(),10);
                if(count == 1 ){
                    me.delCart(id)
                }
                else{
                    me.addCart(id,-1);
                }
                //me.addCart(id,-1);
            }).on('click','.delete',function(e){
                e.preventDefault();
                var id= me.getGoodID($(e.currentTarget).parents('.cart-li'));
                me.delCart(id);
            }).on('focus', '.choose-num', function() {
                $(this).attr('old-val', $(this).val());
            }).on('blur', '.choose-num', function(e){
                var id = me.getGoodID($(this).parents('.cart-li'));
                var count = +$(this).val();
                if (count < 0) {
                    $(this).val($(this).attr('old-val'));
                    return false;
                }
                if (count == 0) {
                    if (confirm('确定删除' + $(this).parents('.cart-li').find('td').eq(1).text() + '吗？')) {
                        me.delCart(id);
                        return true;
                    }
                    else {
                        $(this).val($(this).attr('old-val'));
                        return false;
                    }
                }
                var delta = count - $(this).attr('old-val');
                return me.addCart(id, delta);
            });
        },
        getGoodID : function($el){
            return $el.attr('id').replace(/car/ig,'');
        },

        // TODO:should use templeate
        tempalte : function(carid,numMon,src,name,money,unit_price, buy2){
            if (!this.table_layout) {
                return ''+
                    '<li id="car' + carid + '" class="cart-li' + (buy2?' buy2':'') + '">'+
                    '<div class="qty">'+
                    '<a href="javascript:;" class="change-qty qty-plus"><i></i></a>'+
                    '<div class="qty-num"><em>'+numMon+'</em></div>'+
                    '<a href="javascript:;" class="change-qty qty-minus"><i></i></a>'+
                    '</div>'+
                    '<div class="image"><img src="'+src+'"  / ></div>'+
                    '<div class="name">'+name+'</div>'+
                    '<span class="price">¥<em>'+money+'</em></span>'+
                    '<a href="javascript:;" class="delete delete2" delid="'+carid+'">删除</a>'+
                    '</li>';
            }else if(this.dl_layout){
            	return '<dl class="car' + carid + ' cart-li' + (buy2?' buy2':'') + '"'+'id="' + carid + '">'+
	            '<dd class="one"><h3>产品</h3><img src="' + src + '" width="66" height="66"'+
	            ' alt="' + name + '" title="' + name +'" />'+
	            '<p>' + name + '</p>' +
	             '</dd><dd class="two"><h3>单价</h3><p class="num-s goods-price">¥<em>' + unit_price+'</em></p>'+
	             '</dd><dd class="three"> <h3>数量</h3><h4> <a href="javascript:;" class="choose-qty qty-minus">-</a>'+
	            '<input type="text" class="choose-num" value="'+numMon+'">'+
	            '<a href="javascript:;" class="choose-qty qty-plus">+</a> </h4></dd><dd class="four"><h3>小计</h3>'+
	            '<p class="num-s">¥<span class="goods-price ">'+money+'</span></p> </dd> <div class="clear"></div></dl>'; 
            }
            else {
                return ''+
                    '<tr class="car' + carid + ' cart-li' + (buy2?' buy2':'') + '"'+
                    'id="' + carid + '">'+
                    '<td><img src="' + src + '" width="66" height="66"'+
                    ' alt="' + name + '" title="' + name +
                    '" /></td>"'+
                    '<td>' + name + '</td>'+
                    '<td><span class="goods-price">¥<em>' + unit_price+
                    '</em></span></td>'+
                    '<td><a href="javascript:;" class="choose-qty qty-minus">-</a>'+
                    '<input type="text" class="choose-num" value="'+
                    numMon + '" />' +
                    '<a href="javascript:;" class="choose-qty qty-plus">+</a>'+
                    '</td>'+
                    '<td>¥<span class="goods-price">' + money +
                    '</span></td>'+
                    '<td><a href="javascript:;" class="delete">删除</a></td>'+
                    '</tr>';
            }
        },
        addCart : function(goodId,count){
            var me = this;
            if(!me._ajaxClock){

                me._ajaxClock = true;
                $.get(Cart.APIs.JOIN_CART,{
                    goods_id:goodId,
                    type:'goods',
                    random:Math.random(),
                    goods_num:count
                }).then(function(res){
                    $.isPlainObject(res) || (res = $.parseJSON(res));
                    if(!res.isError){
                    	$.ajaxSetup({
                    	    async : false
                    	});
                        me.showCart();
                    }
                    else{
                        alert(res.message);
                    }
                    me._ajaxClock = false;
                },function(){
                    me._ajaxClock = false;
                })
            }
        },
        showCart : function(){
            var me = this;
            $.getJSON(Cart.APIs.SHOW_CART).then(function(cartInfo){
                me.render(cartInfo);
            });
        },
        render : function(info){
            /*
             总计 cartInfo.sum
             品种 cartInfo.data.length
             单品
             count: 4
             goods_id: 457
             id: 457
             list_img: "upload/2014/08/19/20140819110007167_175_175.jpg"
             name: "夏黑葡萄（当天凌晨采摘，重磅推荐）"
             sell_price: "15.00"
             type: "goods"
             */
            var me = this;
            me.$count.text(info.data.length);
            me.$total.text(info.sum);
            var html = '';
            if(info.data.length){
                $.each(info.data,function(index,item){
                    me.db[item.goods_id] = item;
                    var money = parseFloat(item.sell_price) * parseFloat(item.count);
                    $('#car'+item.goods_id).remove();
                    html += me.tempalte(item.goods_id,item.count,item.list_img,item.name,money.toFixed(2),item.sell_price, item.buy2);
                });
//                resetpay();
            }
            else{
                html += '<div class="empty-cart"><i></i><p>您购物车还未添加商品</p></div>';
            }
            me.$goodList.html(html);
            if(this.dl_layout){
            	$("#productlist dl").eq(0).addClass("firstdl");
            	resetpay();
            }
        },
        delCart : function(goodId){
            var me = this;
            if(!me._ajaxClock){
                me._ajaxClock = true;
                $.getJSON(Cart.APIs.REMOVE_CART,{
                    goods_id:goodId,
                    type:'goods'
                }).then(function(res){
                    $.isPlainObject(res) || (res = $.parseJSON(res));
                    if(!res.isError){
                        me.showCart();
                    }
                    else{
                        alert(res.message);
                    }
                    me._ajaxClock = false;
                },function(){
                    me._ajaxClock = false;
                })
            }
        }
    });

    $.fn.cart = function(options){
        return $(this).data('Cart',new Cart(this,options));
    };
})(jQuery);
(function($){
    function ModelDialog(el,options){
        if(ModelDialog._instance){
           return ModelDialog._instance;
        }
        ModelDialog._instance = this;
        this.$el = $(el);
        this.init(options);
    }
    $.extend(ModelDialog,{
        APIs : {
            PRODUCT : 'http://www.xuxian.com/index.php?controller=site&action=products'
        }
    });
    $.extend(ModelDialog.prototype,{
        init : function(potions){
            var me = this;
            me.$num = $('#buyNums');
            me.$btn = $("#goods_id");
            me.$mask = $("<div class='mask'></div>").hide().appendTo('body');
            $("#goods_id").click(function(){
                me.close();
            });
            me.$el.on('click','.pop-close',function(e){
                e.preventDefault();
                me.close();
            }).on('click','.choose-plus',function(e){
                var val = parseInt(me.$num.val(),10) || 0;
                var leftNum = parseInt(me.$btn.data('leftNum'))||0;
                if(val < leftNum){
                    me.$num.val(val + 1);
                    me.$btn.data('count',val + 1);
                }
            }).on('click','.choose-minus',function(e){
                var val = parseInt(me.$num.val(),10) || 0;
                if(val > 0){
                    me.$num.val(val - 1);
                    me.$btn.data('count',val - 1);
                }
            })


        },
        show : function(id){
            var me = this;
            $.getJSON(ModelDialog.APIs.PRODUCT,{id:id}).then(function(res){
                me.render(res);
            })
        },

        render : function(data){
            var me = this;
            var top,left,maskWidth,maskHeight;
            $("#goods_img").attr("src",data.img);
            $("#goodsname").html(data.name);
            $("#goodsprice").html("<dfn>&yen;</dfn><em>"+data.sell_price +"</em>");
            $("#goodsqty").html(data.unit);
            $("#goodsmarketprice").html("&yen;"+data.market_price);
            $("#content").html(data.content);
            $("#nutrition").html(data.nutrition);
            me.$btn.data({
                id : data.id,
                leftNum : parseInt(data.store_nums) - parseInt(data.sold_num)
            });

            maskWidth = $(document).width();
            maskHeight = $(document).height();

            this.$mask.css({
                width : maskWidth,
                height : maskHeight
            });
            var winHeight = $(window).height();
            left = (maskWidth - me.$el.width()) / 2;
            if(winHeight < me.$el.height()){
                top = $(document).scrollTop()+5;
            }else{
                top = (winHeight-me.$el.height() + $(document).scrollTop())/2 ;
            }
            me.$mask.show();
            me.$el.css({
                "top":top,
                "left":left
            }).show();
        },
        close : function(){
            this.$mask.hide();
            this.$el.hide();
        }
    });
    $.fn.goodDialog = function(){
        return $(this).data('Dialog',new ModelDialog(this,{}));
    }

})(jQuery);
$(function(){
    var $Cart = $('#xuxian-cart').cart();
    var $Dialog = $('#pop-detail').goodDialog();
    $('.btn-cart').live('click',function(ev){
        var $btn = $(this);
        var goodId = $btn.attr('id');
        var cart = $Cart.data('Cart');
        cart.addCart(goodId,1);	
		  $(".cart-drop").slideDown();
		$('.cart-items-list').height("70px").find("li").hide();
		$('#car'+goodId).show();
		
    });
	$('.btn-cart2').live('click',function(ev){
        var $btn = $(this);
        var goodId = $btn.attr('id');
        var cart = $Cart.data('Cart');
        cart.addCart(goodId,1);		
    });
    $("#goods_id").click(function(e){
        var $btn = $(this);
        var id = $btn.data('id');
        var count = $btn.data('count');

        var cart = $Cart.data('Cart');
        cart.addCart(id,count);

    });
    $("#goods_show").click(function(e){
    	var id = $("#goods_show").attr("data");
        var count = $("#qty").val();
        if(count>0){
        	$.ajaxSetup({
        	    async : false
        	});
        	var cart = $Cart.data('Cart');
            cart.addCart(id,count);
            //history.go(-2);
            //window.location.href='/index.php';
        }else{
        	alert("请选择购买数量");
        }
		    $(".cart-drop").slideDown();
		$('.cart-items-list').height("70px").find("li").hide();
			$('#car'+id).show();
    });
    $("#pushgoodsbuy").click(function(e){
    	if($('#goodsc').html()==0){
    		alert("请选择搭配产品");
    	}else{
    		$.ajaxSetup({
        	    async : false
        	});
    		var labels=$(".pushgoods");
    		var bool=false;
    		
    		for(var i=0;i<labels.length;i++){
    			if(labels[i].checked==true){
    				var cart = $Cart.data('Cart');
    	            cart.addCart(labels[i].id,1);
    	            bool=true;
    			}
    		}
    		if(bool){
    			var cart = $Cart.data('Cart');
	            cart.addCart($("#goods_show").attr('data'),1);
    			//window.location.href='/index.php';
    		}
    	}
    });
});
