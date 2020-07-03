<!DOCTYPE html>
<html>
<head>
	<title>导入结果</title>
	<style type="text/css"> 
		.table { 
			border-collapse:collapse; 
		} 
		.table td { 
			border: 1px solid #d0d0d0;border-width: 1px 0 1px 0;padding:3px; 
		}
		.tip_success {
		    font-weight: bold;
		    background-color: green;
		    color: white;
		    padding: 5px;
		    margin:5px 0;
		}
		.ok {
			color:green;
		}
		.failed {
			color:red;
		}
	</style>
	<script type="text/javascript">
		var btn = window.parent.document.getElementById('importBtn'); 
		btn.className = "msbtn disabled";
		btn.disabled = true; 
		btn.value="正在导入,请稍后";
	</script>
</head>
<body>
	{$feedback}
	{$output}
<script type="text/javascript">
	btn.className = "msbtn";btn.disabled = false; btn.value="开始导入";
</script>
</body>
</html>