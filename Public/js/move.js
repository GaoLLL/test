// JavaScript Document
//var timer=null;
function move(obj,attr,iTarget){

	var iSpeed=0;
	iTarget=parseInt(iTarget);
	clearInterval(obj.timer);
	obj.timer=setInterval(function(){
		//判断是否是透明度并且取当前位置
		if(attr=="opacity")
		{
			var iCur=parseInt(parseFloat(getStyle(obj,"opacity"))*100);
		}else
		{
			var iCur=parseInt(getStyle(obj,attr));
		}
		//速度
		if(iCur>iTarget)
		{
			iSpeed=Math.floor((iTarget-iCur)/7);
			
		}else
		{
			iSpeed=Math.ceil((iTarget-iCur)/7);
		}
		//oTxt.value+=iSpeed+"\n";
		//停止条件
		if(iCur==iTarget)
		{
			clearInterval(obj.timer);
		}
		else
		{
			if(attr=="opacity")
			{
				obj.style.opacity=(iCur+iSpeed)/100;
				obj.style.filter="alpha(opacity="+(iCur+iSpeed)+")";
			}else
			{
				obj.style[attr]=iCur+iSpeed+"px";
			}
		}
	},30)
}
function getStyle(obj,attr)
{
	if(obj.currentStyle)
	{
		return obj.currentStyle[attr];
	}else
	{
		return getComputedStyle(obj,false)[attr];	
	}
}