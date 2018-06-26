</div>
<script>
	$(function(){
	    $("input[name=tijiao]").click(function(){
	        $("input[name=page]",$(this).parents("form")).val(1);
	    })
	})
	
{if !empty($feedback)}
	$(function(){
		setTimeout(function(){
			$(".feedback").slideToggle(1000,"linear");
		},5000);
		
	});
{/if}
</script>
</body>
</html>