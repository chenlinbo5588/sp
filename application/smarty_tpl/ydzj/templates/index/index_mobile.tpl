{include file="common/header_main_nav.tpl"}
	{include file="./index_common.tpl"}
	{include file="common/bxslider.tpl"}
	<script type="text/javascript" src="{resource_url('js/TouchSlide.1.1.js')}"></script>
	<script type="text/javascript">
		$(function(){
			$('#homeSwiper').bxSlider({
				autoControls:false,
				controls:false
			});
			
			$('#goinSwiper').bxSlider({
				autoControls:false,
				controls:false
			});
			
			//$('#homeSwiper').cycle({ speed : 3000 , timeout: 3000 ,pager: "#homeSwiperPager",pauseOnPagerHover:true });
			//$('#hotProductSwiper').cycle({ speed : 2000 , timeout: 3000 ,pauseOnPagerHover:true , fx:'scrollRight' });
			$('#hotProductSwiper').bxSlider({
				responsive:true,
				auto:true,
				infiniteLoop:true,
				captions: true,
				touchEnabled:true,
				swipeThreshold:50,
				speed:300,
				pager:false,
				controls:false,
				autoDelay:0,
			    minSlides: 1,
			    maxSlides: 1,
			    moveSlides: 1,
			    slideMargin: 0
			});
		});
	</script>
{include file="common/footer.tpl"}