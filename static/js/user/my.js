/**
 * 个人中心
 */
$(function(){
	$("#logout_link").bind("click",function(e){
		if(confirm("确得要退出吗?")){
			location.href= $(this).attr("data-href");
		}
	});
	
	districtSelect('bind');
});