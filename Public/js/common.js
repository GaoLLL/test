// JavaScript Document
$(function () {
	$("#pointList .block").hover(
		function () {
			$(this).addClass("borderColor")
			$(this).find(".tel").stop().delay(50).animate({"bottom":0,opacity:0.9},200)
		 },
		function () {
			$(this).removeClass("borderColor")
			$(this).find(".tel").stop().animate({"bottom":-22,opacity:0},300)
		}
    )
})

