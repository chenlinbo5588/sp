/**
 * 个人中心
 */
var districtCache = [];

$(function(){
	$("#logout_link").bind("click",function(e){
		if(confirm("确得要退出吗?")){
			location.href= $(this).attr("data-href");
		}
	});
	
	$("#profile_city select").bind("change",function(e){
		var id = $(this).attr("id");
		var name = id.replace('_sel','');
		var index = parseInt(name.replace('d',''));
		
		var upid = $(this).val();
		
		if(index < 4 && upid != ""){
			var targetSel = $("#d" + (index + 1) + "_sel");
			
			for(var i = index; i < 4; i++){
				$("#d" + (i + 1) + "_sel").html('').append('<option value="">请选择</option>');
			}
			
			if(districtCache[upid]){
				showCity(districtCache[upid]);
				return ;
			}
			
			$.getJSON(cityUrl + "/upid/" + upid,function(resp){
				showCity(resp.data);
				districtCache[upid] = resp.data;
			});
		}
		
		function showCity(cityList){
			for(var i = 0; i < cityList.length; i++){
				targetSel.append('<option value="' + cityList[i].id + '">' + cityList[i].name + '</option>');
			}
			
			targetSel.focus();
		}
	});
	
	
});