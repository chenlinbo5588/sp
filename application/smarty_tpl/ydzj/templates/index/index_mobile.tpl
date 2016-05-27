{include file="common/header_main_nav.tpl"}
	{include file="./index_common.tpl"}
	<script type="text/javascript">
		$(function(){
			$('#homeSwiper').cycle({ speed : 3000 , timeout: 3000 ,pager: "#homeSwiperPager",pauseOnPagerHover:true });
			$('#hotProductSwiper').cycle({ speed : 2000 , timeout: 3000 ,pauseOnPagerHover:true , fx:'scrollRight' });
		});
	</script>
{include file="common/footer.tpl"}