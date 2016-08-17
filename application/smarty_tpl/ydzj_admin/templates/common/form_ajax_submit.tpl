var formLock = [];
			
			$("form").each(function(){
				var name=$(this).prop("name");
				formLock[name] = false;
			});
			
			$("form").submit(function(){
				var name=$(this).prop("name");
				var submitBtn = $("input[type=submit]",$(this));
				
				if(formLock[name]){
					return false;
				}
				
				submitBtn.addClass("btndisabled");
				formLock[name] = true;
				
				$.ajax({
					type:'POST',
					url: $(this).prop("action"),
					dataType:'json',
					data:$(this).serialize(),
					success:function(resp){
						formLock[name] = false;
						submitBtn.removeClass("btndisabled");
						
						refreshFormHash(resp.data);
						alert(resp.message);
						if(resp.message != '保存成功'){
							var errors = resp.data.errors;
							var first = null;
							
							for(var f in errors){
								if(first == null){
									first = f;
								}
								$("#error_" + f).html(errors[f]).addClass("error").show();
							}
							$("input[name=" + first + "]").focus();
							
						}else{
							$("label.errtip").hide();
						}
					
					},
					error:function(xhr, textStatus, errorThrown){
						formLock[name] = false;
						submitBtn.removeClass("btndisabled");
						
						alert("服务器发送错误,将重新刷新页面");
					}
				});
				return false;
			});
	    