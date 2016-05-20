{include file="common/main_header.tpl"}
<p style="text-align: center;">对不起，您没有足够的访问权限,请联系管理员</p>
<script>
$(function(){
	setTimeout(function(){
		location.href="{admin_site_url('dashboard/welcome')}";
	},3000);
});
</script>
{include file="common/main_footer.tpl"}