{include file="common/header_main_nav.tpl"}
	{include file="./index_common.tpl"}
	<script type="text/javascript" src="{resource_url('js/jquery.cycle.all.js')}"></script>
	{include file="common/bxslider.tpl"}
	<script type="text/javascript">
		$(function(){
			$('#homeSwiper').cycle({ speed : 3000 , timeout: 3000 ,pager: "#homeSwiperPager",pauseOnPagerHover:true });
			$('#goinSwiper').bxSlider({
				infiniteLoop:true,
				auto:true,
				touchEnabled:true,
				useCSS: false,
				easing: 'easeOutCubic',
				speed:2000,
				captions: true
			});
			
			
			$('#hotProductSwiper').bxSlider({
				responsive:true,
				auto:true,
				infiniteLoop:true,
				captions: true,
				touchEnabled:true,
				swipeThreshold:50,
				speed:300,
				pager:false,
				autoDelay:0,
				slideWidth: 120,
			    minSlides: 10,
			    maxSlides: 20,
			    moveSlides: 1,
			    slideMargin: 10
			});
			
		});
	</script>
{include file="common/footer.tpl"}