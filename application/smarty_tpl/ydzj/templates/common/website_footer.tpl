		<script>
	    $(function(){
	        {if $isMobile}
	        $("#naviText").bind("click",function(){
	            $("#homeNav").slideToggle('fast');
	        });
	        {else}
	        $("li.level0").bind("mouseenter",function(){
	            $(this).addClass("current");
	            /*
	            $(this).addClass("current").siblings(".sublist").css({ opacity: 0}).show().animate({
	                speed:500,
	                opacity:1,
	                height:"100%"
	            });
	            */
	        }).bind("mouseleave",function(){
	            $(this).removeClass("current");
	        });
	        {/if}
	    });
	    </script>
{include file="common/footer.tpl"}