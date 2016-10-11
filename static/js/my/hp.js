;$(function(){
	$( ".datepicker" ).datepicker({
		 numberOfMonths: 2,
		 showButtonPanel: false,
		 changeMonth: true,
		 changeYear: true,
		 minDate: 0
	})
	
	function addRow(){
		if(!rowControl()){
			return;
		}
		
		var row = $("#rowTpl").html();
		$("#bodyContent").append($(row));
		
		add_index();
	}
	
	function add_index(){
		$("#bodyContent tr").each(function(){
			var tr = $(this);
			tr.find("td:eq(0)").html(tr.index() + 1);
		})
	}
	
	function rowControl(){
		if($("#bodyContent tr").size() >= maxRow){
			
			$.toast({
	            position:'bottom-center',
	            text: '最多' + maxRow + '行',
	            icon: 'info',
	            stack:1,
	            hideAfter:3000,
	            bgColor: '#324DFF',
	            textColor: 'white',
	            loader:false
	        });
			
			return false;
		}
		
		return true;
		
	}
	
	
	
	$("input[name=addrow]").bind("click",addRow);
	$("input[name=clearall]").bind('click',function(){
		$("#bodyContent").html('');
	});
	
	$("#bodyContent").delegate("a.copyrow","click",function(){
		if(!rowControl()){
			return;
		}
		
		var alink = $(this),size;
		
		var tr = $(this).parents('tr');
		var cloneTr = tr.clone(false);
		var sizeInput = cloneTr.find("input[name='goods_size[]']");
		
		cloneTr.find("label.error").remove();
		cloneTr.find("input").removeClass("error").removeClass("valid");
		
		size = $.trim(sizeInput.val());
		size = parseFloat(size);
		
		if(!size){
			size = 0;
		}
		
		if(alink.hasClass("incre")){
			sizeInput.val(size + 1);
		}else if(alink.hasClass("decre")){
			if((size - 1) > 0){
				sizeInput.val(size - 1);
			}
		}
		
		cloneTr.insertAfter(tr);
		add_index();
	});
	
	$("#bodyContent").delegate("a.deleterow","click",function(){
		var tr = $(this).parents('tr');
		tr.remove();
		add_index();
	});
	
	
	var stepBy = function(sizeInput,op){
		var size = $.trim(sizeInput.val());
		size = parseFloat(size);
		
		if(!size){
			size = 0;
		}
		
		if('up' == op){
			sizeInput.val(size + 0.5);
		}else if('down' == op){
			if((size - 0.5) > 0){
				sizeInput.val(size - 0.5);
			}
		}
	}
	
	$("#bodyContent").delegate("a.fa-long-arrow-down,a.fa-long-arrow-up","click",function(){
		var tr = $(this).parents('tr');
		var sizeInput = tr.find("input[name='goods_size[]']");
		
		if($(this).hasClass("fa-long-arrow-down")){
			stepBy(sizeInput,'down');
		}else if($(this).hasClass("fa-long-arrow-up")){
			stepBy(sizeInput,'up');
		}
	});
	
	/*
	var formValidation = $("#pubForm").validate({
		submitHandler:function(){
			//sendPm();
		},
		rules: {
			"goods_code[]": {
				required:true,
				minlength: 1,
				maxlength: 10
			},
			"goods_name[]" : {
				required:true,
				minlength: 1,
				maxlength:20
			},
			"goods_color[]" : {
				required: true,
				minlength: 1,
				maxlength:5
			},
			"goods_size[]": {
				required: true,
				number:true,
				min:5,
				max:60
			},
			"quantity[]" : {
				required: true,
				min:1,
				max:500,
				digits:true
			},
			"send_day" : {
				dateISO:true
			}
		},
		messages: {
			"goods_code[]":{
				required:"必填"
			},
			"goods_name[]":{
				required:"必填"
			},
			"goods_color[]" : {
				required: "必填"
			},
			"goods_size[]": {
				required: "必填",
				number:"输入数字",
				min:"最小{0}",
				max:"最大{0}"
			},
			"quantity[]" : {
				required: "必填",
				digits:"输入整数"
			}
		}
	});
	*/
});