#regbg {
	background:#e9e9e9;
	position: relative;
	padding:15px 25px;
}

img.responed {
	display: block;
}

#regbg div {
	margin:5px 0;
	position: relative;
}


#reg .txt {
	width:100%;
	border:1px solid #e0e0e0;
	height:37px;
	line-height:37px;
    text-indent: 40px;
}

.username .txt, .stock .txt {
	background:#fff url('{resource_url("img/pg1/name_bg.png")}') no-repeat 10px center;
}

.mobile .txt {
	background:#fff url('{resource_url("img/pg1/mobile_bg.png")}') no-repeat 10px center;
}

.auth_code .txt {
	background:#fff url('{resource_url("img/pg1/code_bg.png")}') no-repeat 10px center;
}

#reg .getCode {
	height: 37px;
	line-height:37px;
	text-align:center;
	background:#d3d5da;
	border:0px;
	position: absolute;
    right: 0;
    font-size: 14px;
    width:28%;
    border-radius:0px;
    -webkit-border-radius:0px;
    -moz-border-radius:0px;
    -o-border-radius:0px;
    -webkit-appearance:none;
}

#reg .sb {
	
	height:50px;
}

#reg .sb input {
	text-indent:-1000em;
	display:block;
	height:50px;
	background:#e9e9e9 url('{resource_url("img/new_pg1/reg.jpg")}') no-repeat center center;
	border:0;
}

form label.error {
	display:block;
}