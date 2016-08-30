	</div><!-- end of page -->
</div><!-- end of main-content -->
<script>
	$(function(){
		{if !empty($feedback)}
		setTimeout(function(){
			$(".feedback").slideToggle(1000,"linear");
		},3000);
		{/if}
	});
</script>
</body>
</html>