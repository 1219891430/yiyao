// JavaScript Document
$(document).ready(function() {
    $(window).on("load",function(){
		imgLocation();
		var dataImg = {"data":[{"src":"1.jpg"},{"src":"2.jpg"},{"src":"3.jpg"},{"src":"4.jpg"},{"src":"5.jpg"},{"src":"6.jpg"}]};
		window.onScroll = function(){
			if(scrollside()){
				$.each(dataImg.data,function(index,value){
					var box = $("<div>").addClass("photo-box").appendeTo($("#container"));
					var content = $("<div>").addClass("content").appendTo(box);
					console.log("./img/"+$(value).attr("src"));
					//$("<img>").attr("src","./img/"+$(value).attr("src")).appendTo(content);
				});
				imgLocation();
			};
		};
	})
});

function scrollSide(){
	var box = $(".photo-box");
	var lastboxHeight = box.last().get(0).offsetTop+Math.floor(box.last().height()/2);
	var scrollHeight = $(window).scrollTop();
	return(lastboxHeight<scrollHeight+documentHeight)?true:false;
}

function imgLocation(){
	var box = $(".photo-box");
	var boxWidth = box.eq(0).width();
	var num = Math.floor($(".photo-container").width()/boxWidth);	<!--每排能放的个数取整数-->
	var boxArr=[];
	box.each(function(index, value) {
		var boxHeight = box.eq(index).height();
		if(index<num){
			boxArr[index] = boxHeight+20;
		}else{
			var minboxHeight = Math.min.apply(null,boxArr);
			var minboxIndex = $.inArray(minboxHeight,boxArr);  /*获取高度最小元素的位置*/
			$(value).css({
				"position":"absolute",
				"top":minboxHeight,
				"left":box.eq(minboxIndex).position().left
			});
			boxArr[minboxIndex]+=box.eq(index).height()+20;
		}
    });
}