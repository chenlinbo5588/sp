{include file="common/new_detail_header.tpl"}
	<style type="text/css" mce_bogus="1">			/*反馈窗口*/
		#feedback{
			width:50px;
			height:120px;
			position:fixed;
			left:25%;
			margin-top:70px;
		}
		
		.windows_feedback{ width:29px; height:120px; z-index: 1000;}
		.windows_feedback a{ display:block; width:19px; margin:0 auto; padding-top:5px}
		.windows_feedback a:hover{ color:#ff7100; text-decoration:none}
		.window_suspend{ width:19px; background:url(http://www.dangdang.com/images/bg_feedback_01.gif) no-repeat 0 0;}
		.window_suspend a{ display:block; background:url(http://www.dangdang.com/images/bg_feedback_02.gif) no-repeat 0 bottom; width:15px; padding:7px 0 6px 4px; text-decoration:none; font-size:12px; color:#878787;}
		.window_suspend a:hover{ color:#f60; text-decoration:none;}
		
		-webkit-tap-highlight-color: rgba(255, 255, 255, 0);
		-webkit-user-select: none;
		-moz-user-focus: none;
		-moz-user-select: none;
		
		.navigationBarText{ font-weight:bold; margin-right:5px; color:black;}
		a:link { 
			font-size: 12px; 
			color: #343434; 
			text-decoration: none; 
		} 
		a{ text-decoration:none} 
		a:visited { 
			font-size: 12px; 
			color: #343434; 
			text-decoration: none; 
		} 
		a:hover { 
			font-size: 12px; 
			color: #999999; 
			text-decoration:none;
		}
		.box{
			display:flex; 
			flex-direction: column;
		}
		.boxh{
			display:flex; 
		}
		.imgbox{
			width:40px;
			height:40px;
		}
		.czjz{
			justify-content:center;
			align-items:center;
		}
		.jz{
			align-items:center;
		}
		.spjz{
			justify-content:center;
		}
		.new{
			white-space:nowrap;
			overflow:hidden;
			text-overflow:ellipsis;
		}
		.newbox{
			width:260px;
			height:40px;
			
			
			
		}
		.line{
			border:1px solid #f2f2f2;
			width:100%;
		}
		.more{
			width:260px;
			height:100%;
			margin-top:150px;
			margin-left:15px;
		}
		
		.s1{
			position: absolute;
			display: none;
			right:55px;
		}
		.s2{
			position: absolute;
			display: none;
			right:55px;
		}
		.s3{
			position: absolute;
			display: none;
			right:55px;
		}
		.notFindBox{
			width:20%;
			margin-left:40%;
			height:200px;
			border:1px solid black;
			
			
		}
		.dianji{
			cursor:pointer;
		}
		img{
			max-width:80%;
		}
	</style>
	<script>
	
		var times = 4;
		if({$isNotFind} == true){
			clock();
		}
	    function clock() {   
	      	window.setTimeout(clock,1000);
	        times=times-1;
	        $("#time").attr("value",times);
	        if(times == 0){
        		window.location = "{base_url('index.php/news/index')}";
	        }
	      
	    }
		
		function show(){
				var id =window.event.srcElement.id;
				id ='s'+id;
				document.getElementById(id).style.display="block";
				
		}
		function dis(){
			var id =window.event.srcElement.id;
			id ='s'+id;
			document.getElementById(id).style.display="none";
		}
		
		
	
	
		function gotoTop(acceleration, stime) {
	        acceleration = acceleration || 0.1;
	        stime = stime || 10;
	        var x1 = 0;
	        var y1 = 0;
	        var x2 = 0;
	        var y2 = 0;
	        if (document.documentElement) {
	            x1 = document.documentElement.scrollLeft || 0;
	            y1 = document.documentElement.scrollTop || 0;
	        }
	        if (document.body) {
	            x2 = document.body.scrollLeft || 0;
	            y2 = document.body.scrollTop || 0;
	        }
	        var x3 = window.scrollX || 0;
	        var y3 = window.scrollY || 0;
	
	        // 滚动条到页面顶部的水平距离
	        var x = Math.max(x1, Math.max(x2, x3));
	        // 滚动条到页面顶部的垂直距离
	        var y = Math.max(y1, Math.max(y2, y3));
	
	        // 滚动距离 = 目前距离 / 速度, 因为距离原来越小, 速度是大于 1 的数, 所以滚动距离会越来越小
	        var speeding = 1 + acceleration;
	        window.scrollTo(Math.floor(x / speeding), Math.floor(y / speeding));
	
	        // 如果距离不为零, 继续调用函数
	        if (x > 0 || y > 0) {
	            var run = "gotoTop(" + acceleration + ", " + stime + ")";
	            window.setTimeout(run, stime);
	        }
	    }

	</script>
		<div class="box" style="width:100%;{if $isNotFind=="false"}display:none;{/if}" >
			<div class ="box notFindBox"  >
				<div class="box spjz " style="width:100%; height:30px;   background-color:#f4f4f4">
					<div style="margin-left:20px;">您找的网页不见了</div>
				</div>
				<div class="" style="border-bottom:1px solid black;"></div>
				<div class="box czjz" style="height:170px; font-weight:bold;">该新闻不存在或者审核未通过。</div>
			</div>
			<div class="boxh dianji czjz" style="width:20%; margin-left:40%;margin-top:20px;border" >
				<button class="boxh dianji czjz" style="padding:3px 5px; " onclick="window.open('{base_url('index.php/news/index')}','_self')">
					<input id="time" value="3" style="border:none;width:7px; color:red; background-color:#f4f4f4;" class="box czjz" ></input>
					<div class="box ">秒后跳转到新闻页</div>
				</button>
			</div>
		</div>
		<div class ="boxh " style="{if $isNotFind=="true"}display:none;{/if}">
  			<div style="width:40%; margin-left:30%;" class="box">
	        	<div style="display:flex; font-size:14px; " >
	        		<div class="navigationBarText" href="{base_url('index.php/index/index')}">
	        			<a href="{base_url('index.php/news/index')}" style="" >首页 ></a></div>
	        		<div class="navigationBarText"  >
	        			<a href="{base_url('index.php/news/index')}">新闻中心 ></a></div>
	        		<div style="color:#8d8d8d; font-size:12px;">正文</div>
	        	</div>
	       		<div style="display:flex; font-size:14px; margin-top:25px;">
	       			<h class="w-title" style="font-size:36px; line-height:45px;">{$info['article_title']}</h>
	       		</div>
	       		<div style="margin-top:20px;" >
	        		<div style="color:#c7c7c7;font-size=10px">{$time}</div>
	        	</div>
	        	<div style="margin-top:35px;">
	        		<hr/ style="width:100%; background-color: #cdcccc;border:none; height:1px;" />
	        	</div>
	        	<div class="windows_feedback" id="feedback" style="display:none;" > 
	        		<div class= "box">
		                <div  style= "margin-top:3px;" class="box" >
		                	<div class="s1 box" id="simage1">
		                		<div style="width:120px;height:50px;border:1px solid #000;">邮箱地址:123123131</div>
		             		</div>
		                	<img class="imgbox " src="{resource_url('img/btns/phone.jpg')}"  id="image1" onmouseover="show();" onmouseout="dis();" />
		                </div>
		                <div  style= "margin-top:3px;" class="box" >
		                	<div class="s2 box" id="simage2">
		                		<div style="width:120px;height:50px;border:1px solid #000;">邮箱地址:123123131</div>
		             		</div>
		                	<img class="imgbox " src="{resource_url('img/btns/weixin.jpg')}" id="image2" onmouseover="show();" onmouseout="dis();" />
		                </div>
		                <div  style= "margin-top:3px;" class="box" >
		                	<div class="s3 box" id="simage3">
		                		<div style="width:120px;height:50px;border:1px solid #000;">邮箱地址:123123131</div>
		             		</div>
		                	<img class="imgbox " src="{resource_url('img/btns/email.jpg')}" id="image3"  onmouseover="show();" onmouseout="dis();"/>
		                </div>
		                 <div onclick="gotoTop();return false;" style="margin-top:30px; cursor:pointer;" >
		                	<img class="imgbox" src="{resource_url('img/btns/zhiding.png')}" />
		                </div>
	      			</div>
	   			</div>
	   		
				<div style="margin-top:35px; line-height:30px;">
					{$info['content']}
				</div>
				<div style="margin-top:50px;">
	        		<hr/ style="width:100%; background-color: #cdcccc;border:none; height:1px;" />
	        	</div>
        	</div>
        	<div class="more" > 
	   				<div class="box"> 
		                <div class="boxh"> 
		                	<div style="width:4px;height:30px;background-color:#3d85c6;"></div>
		                	<div style="width:100%;height:30px;background-color:#f6f6f6;" class="jz boxh">
		                		<div style="font-weight:bold;font-size:12px;margin-left:10px;">新闻推荐</div>
		                	</div>
		                </div>
		                <div class="box">
		                	{foreach from=$news item=item}
			                	<div class="box jz " >
			                		<div class="jz boxh newbox" style="margin-left:15px">
			                			<a href="{base_url('index.php/news/detail?id=')}{$item['id']}" class="new">{$item['article_title']}</a>
			                		</div>
			                		<div class="line"></div>
			                	</div>
		                	{/foreach}
		                </div>
	                </div>
	      
	   			</div>
        	
	 </div>
	
	
{include file="common/footer.tpl"}
	