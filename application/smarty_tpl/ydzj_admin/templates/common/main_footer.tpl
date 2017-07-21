	</div><!-- end of page -->
{if !empty($feedback)}
<script>
	$(function(){
		setTimeout(function(){
			$(".feedback").slideToggle(1000,"linear");
		},3000);
		
	});
</script>
{/if}
<!--[if lt IE 9]>
<script type="text/javascript" src="{resource_url('js/html5shiv.js')}"></script>
<script type="text/javascript" src="{resource_url('js/respond.min.js')}"></script>
<![endif]-->
</body>
</html>