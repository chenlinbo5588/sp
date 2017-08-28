{include file="common/my_header.tpl"}
<p class="error" style="text-align: center;">对不起，您没有足够的访问权限,请联系管理员</p>
<script>
$(function(){
	setTimeout(function(){
		location.href="{site_url('my/base')}";
	},3000);
});
</script>
{include file="common/my_footer.tpl"}