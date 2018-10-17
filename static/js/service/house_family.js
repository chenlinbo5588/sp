$(function(){
	
	var mobileFn = {
		    source: function( request, response ) {
			$.ajax( {
	            url: searchYezhuUrl,
	            dataType: "json",
	            data: {
	              term: request.term,
	              resident_id:$("input[name=resident_id]:checked").val(),
	            },
	            success: function( data ) {
	              response( data );
	            }
	          } 
			);
      },
	      minLength: 2,
	      select: function( event, ui ) {
	    	  console.log(event);
	    	  
	    	  $(event.target).parent('td').parent('tr').find("input[name='name[]']").val(ui.item.name)
	    	//$("input[name=yezhu_name]").val(ui.item.name);
			//$("input[name=yezhu_id]").val(ui.item.id);
	        
	      }
	    };
	
	
	$( ".mobile" ).autocomplete(mobileFn);
	
	
	$("body").delegate('.dynBtn','click',function(){
		
		var that = $(this);
		console.log(that);
		
		var html = $("#templateRow").html();
		
		//html.replace(/\[\]/,'\\[\\]');
		
		var newRow = $(html);
		
		$( ".mobile",newRow ).autocomplete(mobileFn);
		
		if(that.val() == '添加'){
			$("#familyConfigTable tbody").append(newRow);
		}else{
			
			if(that.parent().parent().index() != 0){
				that.parent().parent().remove();
			}
		}
	});
	
});