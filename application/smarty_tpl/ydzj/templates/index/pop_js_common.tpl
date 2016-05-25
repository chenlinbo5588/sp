$("#reg h4 a").bind("click",function(){
				$("#maskbg").hide();
				$("#regbg").hide();
			});
			
			$(".cv").bind("click",function(){
				$(".tiparea").html('');
				$("input[type=text]").removeClass("error");
				
				
				
				var doc_height = $(document).height();
				var pop_height = $("#regbg").height();
				var left = 0;
                var top  = 0;
                $("#regbg").css( { visibility: 'hidden' } );
                
                $("#maskbg").css({ width:'100%',height:doc_height} ).show();
                
                
                var dialog_width    = $("#regbg").width();
                var dialog_height   = $("#regbg").height();
                
                left = $(window).scrollLeft() + ($(window).width() - dialog_width) / 2;
                top  = $(window).scrollTop()  + ($(window).height() - dialog_height) / 2;

                $("#regbg").css({ left:left + 'px', top:top + 'px',visibility: 'visible'}).show();
			});