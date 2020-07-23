 var bgObj,msgObj;
        function alertWin(title, msg, w, h) {
            $(".drop-account").removeClass("drop-account-hover");
             
            var titleheight = "35px"; // 窗口标题高度
            var bordercolor = "#60ab59"; //窗口的边框颜色
            var titlecolor = "#FFFFFF"; // 窗口的标题颜色
            var titlebgcolor = "#60ab59"; // 窗口的标题背景色
            var bgcolor = "#FFFFFF"; // 内容背景色
            var iWidth = document.documentElement.clientWidth; //这个窗口的宽度
            var iHeight = document.documentElement.clientHeight; //这个窗口的高度
 
            //背景层的格式
             bgObj = document.createElement("div");
            
            bgObj.style.cssText = "position:absolute;left:0px;top:0px;width:" + iWidth + "px;height:" + Math.max(document.body.clientHeight, iHeight) + "px;filter:Alpha(Opacity=30);opacity:0.3;background-color:#000000;z-index:1000;";
            
            document.body.appendChild(bgObj);
            
            var iframe2 = document.createElement("iframe");
            iframe2.style.cssText = "position:absolute; top:0px;filter:Alpha(Opacity=30);opacity:0.3;background-color:#000000; z-index:1001; border-style:none; border-width:0px; border:0px;width:" + iWidth + "px;height:" + iHeight + "px";
            bgObj.appendChild(iframe2);
           
            //创建一个弹出层
             msgObj = document.createElement("div");
            //设置弹出的层的样式
			msgObj.className="yhjbox";
           <!-- msgObj.style.cssText = "position:absolute;font:11px '宋体';top:" + (iHeight - h) / 2 + "px;left:" + (iWidth - w) / 2 + "px;width:" + w + "px;height:" + h + "px;text-align:center;border:1px solid " + bordercolor + ";background-color:" + bgcolor + ";padding:1px;line-height:22px;z-index:1001;";-->
            document.body.appendChild(msgObj);
            //创建一个table用于容纳层上的内容
            var table = document.createElement("table");
            //将Table放到弹出层上
            msgObj.appendChild(table);
            //设置table的格式
            table.style.cssText = "margin:0px;border:0px;padding:0px;";
            table.cellSpacing = 0;
            //插入一行用于显示标题
            var tr = table.insertRow(-1);
            //插入一个单元格用于容纳标题
            var titleBar = tr.insertCell(-1);
            titleBar.style.cssText = "width:100%;height:" + titleheight + ";text-align:left;font:bold 16px '宋体';color:" + titlecolor + ";cursor:move;background-color:" + titlebgcolor;
            titleBar.style.paddingLeft = "10px";
            //设置标题
            titleBar.innerHTML = title;
            //设置曾德拖动事件
            var moveX = 0;
            var moveY = 0;
            var moveTop = 0;
            var moveLeft = 0;
            var moveable = false;
            var docMouseMoveEvent = document.onmousemove;
            var docMouseUpEvent = document.onmouseup;
            //鼠标点击标题
            titleBar.onmousedown = function() {
                var evt = getEvent();
                moveable = true;
                moveX = evt.clientX;
                moveY = evt.clientY;
                moveTop = parseInt(msgObj.style.top);
                moveLeft = parseInt(msgObj.style.left);
                //鼠标拖动
                document.onmousemove = function() {
                    if (moveable) {
                        var evt = getEvent();
                        var x = moveLeft + evt.clientX - moveX;
                        var y = moveTop + evt.clientY - moveY;
                        if (x > 0 && (x + w < iWidth) && y > 0 && (y + h < iHeight)) {
                            msgObj.style.left = x + "px";
                            msgObj.style.top = y + "px";
                        }
                    }
                };
                document.onmouseup = function() {
                    if (moveable) {
                        document.onmousemove = docMouseMoveEvent;
                        document.onmouseup = docMouseUpEvent;
                        moveable = false;
                        moveX = 0;
                        moveY = 0;
                        moveTop = 0;
                        moveLeft = 0;
                    }
                };
            }
            
            //关闭按钮事件
            var closeBtn = tr.insertCell(-1);
            closeBtn.style.cssText = "cursor:pointer; text-align:right;padding:2px;background-color:" + titlebgcolor;
            closeBtn.innerHTML = "<span style='font-size:16pt;color:" + titlecolor + ";margin-right:10px'>×</span>";
            closeBtn.onclick = function() {
                document.body.removeChild(bgObj);
                document.body.removeChild(msgObj);
            }
             
            //弹出的消息窗口内容
            var msgBox = table.insertRow(-1).insertCell(-1);
           // msgBox.style.cssText = "font:10pt '宋体';";
            msgBox.colSpan = 2;
            msgBox.innerHTML = msg;
            //层上模板名称的内容
            var nameBox = table.insertRow(-1);
            var nameLable = nameBox.insertCell(-1);
           // nameLable.style.cssText = "font:10pt '宋体';text-align:center;";
		   nameLable.colSpan = 2;
            nameLable.innerHTML = "<p class='yhjtitle'>本券在大促活动中无法使用,请您消费时注意</p>";
            var inputBox = table.insertRow(-1);
            var inputBoxtext = inputBox.insertCell(-1);
			inputBoxtext.colSpan=2;
            //inputBoxtext.style.cssText = "float:left;margin-left:150px;line-height:25px";
            inputBoxtext.innerHTML = "<input type='text' value='' id='modalName' class='yhj_input' value='请输入优惠券号码'/> <input type='Button' value='确 定' id='saveHeader' class='yhj_submit' onclick='return GetCark()' />  ";
            //var submitBtn = inputBox.insertCell(-1);
           // submitBtn.style.cssText = "float:left;margin-left:20px";
           // submitBtn.innerHTML = "   ";
            //层上动作按钮的内容
//             var submitBox = table.insertRow(-1);
//             var submitBtn = submitBox.insertCell(-1);
//             submitBtn.style.cssText = "text-align:center;";
//             submitBtn.colSpan = 1;
//             submitBtn.innerHTML = "<br/><input type='Button' value='确 定' id='saveHeader'style='background-color:#60ab59;width:80px;color:#fff;height:30px;' onclick='return GetCark()' /><br/>";
            function getEvent() {
                return window.event || arguments.callee.caller.arguments[0];
            }
            
        }
        function GetCark(){
            var couponcode=$('#modalName').val();
            if(couponcode!=''){
            	$.ajax( {
        			async: false, 
        			type : "POST",
        			url : "http://www.xuxian.com/index.php?controller=simple&action=getcoupon",
        			data : {couponcode:couponcode},
        			dataType: "json",
        			success : function(data) {
            			alert(data.message);
        				document.body.removeChild(bgObj);
                        document.body.removeChild(msgObj);
        			},
        			error :function(){
        				alert("系统繁忙请稍后再试");
        				document.body.removeChild(bgObj);
                        document.body.removeChild(msgObj);
        			}
        			});
               }else{
                document.body.removeChild(bgObj);
                document.body.removeChild(msgObj);
               }
            }