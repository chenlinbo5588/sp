;$(function(){
	
	var dtFn = function(){
		$( ".datepicker" ).datepicker({
			 numberOfMonths: 2,
			 showButtonPanel: false,
			 changeMonth: true,
			 changeYear: true,
			 minDate: 0
		})
	}
	
	function addRow(){
		if(!rowControl()){
			return;
		}
		
		var row = $("#rowTpl").html();
		$("#bodyContent").append($(row));
		
		
		dtFn();
		
		
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
	
	$( document ).tooltip({
      items: "img,[title]",
      content: function() {
          var element = $( this );
          
          if ( element.is( "[title]" ) ) {
            return element.attr( "title" );
          }
          
          
          if ( element.is( "img" ) ) {
            return element.attr( "alt" );
          }
        }
	});
	
	
	
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
		
		if(size){
			if(alink.hasClass("incre")){
				sizeInput.val(size + 1);
			}else if(alink.hasClass("decre")){
				if((size - 1) > 0){
					sizeInput.val(size - 1);
				}
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
		
		if(size){
			if('up' == op){
				sizeInput.val(size + 0.5);
			}else if('down' == op){
				if((size - 0.5) > 0){
					sizeInput.val(size - 0.5);
				}
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
	
	dtFn();
});