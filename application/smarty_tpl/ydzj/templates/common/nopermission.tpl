{include file="common/header.tpl"}
<p style="text-align: center;">对不起，您没有足够的访问权限,请联系管理员</p>
<script>

$(function(){

	setTimeout(function(){
		top.location.href="{admin_site_url('index/index')}";
	},3000);
});
</script>
{include file="common/footer.tpl"}