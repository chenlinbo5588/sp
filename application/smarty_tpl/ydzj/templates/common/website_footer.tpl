		<script>
	    $(function(){
	        {if $isMobile}
	        $("#naviText").bind("click",function(){
	            $("#homeNav").slideToggle('fast');
	        });
	        {else}
	        $("li.level0").bind("mouseenter",function(){
	            $(this).addClass("current");
	        }).bind("mouseleave",function(){
	            $(this).removeClass("current");
	        });
	        {/if}
	    });
	    </script>
{include file="common/footer.tpl"}