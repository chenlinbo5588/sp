{include file="./site_common.tpl"}
<style type="text/css">

.formdiv {
	position:relative;
}

#regbg {
	padding: 20px 0;
	position: absolute;
    width: 100%;
    top:0px;
}

img.responed {
	display: block;
}

#reg {
	margin: 0 auto;
	width:95%;
}


form label.error {
	padding-left:0px;
   margin-left:0px;
	background:none;
	    display: block;
    width: 100%;
    text-align: center;
}

.username ,.mobile, .auth_code {
	margin:2px 0;
}


.username .side_lb,.mobile label, .auth_code .side_lb {
	float:left;
	height:35px;
	line-height:35px;
	display:block;
	width:15%;
	color:#0D0D0D;
}

.username .txt,.mobile .txt , .auth_code .txt {
	width:84%;
	height:35px;
	line-height:35px;
	float:right;
}

.auth_code {
	position:relative;
}

.auth_code .txt {
	
}
.auth_code img {
	position:absolute;
	margin-right:0;
	right:0;
	top:0;
}


.refresh {
	padding:2px 0;
}

.refresh a {
	display:block;
	width:100%;
	text-align:center;
	color:#61615D;
}

.btn2 {
	position:relative;
}

.btn2 input {
	background:#d31717;
	height:34px;
	line-height:34px;
	text-align:center;
	border:0;
	color:#fff;
	font-size:15px;
	font-weight:bold;
	width:100%;
}



.fillcol {
	background:#c0eaff;
	height:160px;
}

</style>
	<div id="wrap">
		<div>
   			<img class="responed" src="{resource_url('img/pg6/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg6/pic2.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg6/pic3.jpg')}"/>
   		</div>
   		<div class="cv">
   			<img class="responed" src="{resource_url('img/pg6/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg6/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg6/pic6.jpg')}"/>
   		</div>
		<div>
   			<img class="responed" src="{resource_url('img/pg6/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg6/pic8.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/pg6/pic9.jpg')}"/>
   		</div>
   		<div>
   			{include file="./site_f1.tpl"}
   			<img class="responed" src="{resource_url('img/pg6/pic10.jpg')}"/>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/pg6/pic11.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
</body>
</html>