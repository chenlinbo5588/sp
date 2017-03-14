{include file="common/main_header.tpl"}
{config_load file="seoset.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>SEO设置</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" nctype="index" class="current"><span>首页</span></a></li>
        {*<li><a href="JavaScript:void(0);" nctype="group"><span>团购</span></a></li>
        <li><a href="JavaScript:void(0);" nctype="brand"><span>品牌</span></a></li>
        <li><a href="JavaScript:void(0);" nctype="credits"><span>积分中心</span></a></li>*}
        <li><a href="JavaScript:void(0);" nctype="article"><span>文章</span></a></li>
        {*<li><a href="JavaScript:void(0);" nctype="shop"><span>店铺</span></a></li>*}
        <li><a href="JavaScript:void(0);" nctype="product"><span>商品</span></a></li>
        <li><a href="JavaScript:void(0);" nctype="category"><span>商品分类</span></a></li>
        {*<li><a href="JavaScript:void(0);" nctype="sns"><span>SNS</span></a></li>*}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"><div class="title">
            <h5>操作提示</h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        	<ul>
	        	<li>插入的变量必需包括花括号“{#kuohao#}”，当应用范围不支持该变量时，该变量将不会在前台显示(变量后边的分隔符也不会显示)，留空为系统默认设置，SEO自定义支持手写。以下是可用SEO变量: <br/><a href="javascript:void(0);" id="toggmore">显示/隐藏全部提示...</a></li>
	            <li>站点名称 {#sitename#}，（应用范围：全站）</li>
	            <li nctype="vmore">名称 {#name#}，（应用范围：团购名称、商品名称、品牌名称、文章标题、分类名称）</li>
	            <li nctype="vmore">文章分类名称 {#article_class#}，（应用范围：文章分类页）</li>
	            <li nctype="vmore">店铺名称 {#shopname#}，（应用范围：店铺页）</li>
	            <li nctype="vmore">关键词 {#key#}，（应用范围：商品关键词、文章关键词、店铺关键词）</li>
	            <li nctype="vmore">简单描述 {#description#}，（应用范围：商品描述、文章摘要、店铺关键词）</li>
          	</ul>
          </td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url('setting/seoset'),'name="form_index"')}
	<span style="display:none" nctype="hide_tag"><a>{#sitename#}</a></span>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>首页</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[index][title]" name="SEO[index][title]" value="{$currentSetting['index']['title']|escape}" class="w300" type="text"/></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[index][keywords]" name="SEO[index][keywords]" value="{$currentSetting['index']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[index][description]" name="SEO[index][description]" value="{$currentSetting['index']['description']|escape}" class="w300" type="text" /></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {*
  {form_open(admin_site_url('setting/seoset'),'name="form_group"')}
    <span style="display:none" nctype="hide_tag"><a>{#sitename#}</a><a>{#name#}</a></span>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>团购</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[group][title]" name="SEO[group][title]" value="{$currentSetting['group']['title']|escape}" class="w300" type="text"/></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[group][keywords]" name="SEO[group][keywords]" value="{$currentSetting['group']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[group][description]" name="SEO[group][description]" value="{$currentSetting['group']['description']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>团购内容</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[group_content][title]" name="SEO[group_content][title]" value="{$currentSetting['group_content']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[group_content][keywords]" name="SEO[group_content][keywords]" value="{$currentSetting['group_content']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[group_content][description]" name="SEO[group_content][description]" value="{$currentSetting['group_content']['description']|escape}" class="w300" type="text" /></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {form_open(admin_site_url('setting/seoset'),'name="form_brand"')}
    <span style="display:none" nctype="hide_tag"><a>{#sitename#}</a><a>{#name#}</a></span>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>品牌</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[brand][title]" name="SEO[brand][title]" value="{$currentSetting['brand']['title']|escape}" class="w300" type="text"/></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[brand][keywords]" name="SEO[brand][keywords]" value="{$currentSetting['brand']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[brand][description]" name="SEO[brand][description]" value="{$currentSetting['brand']['description']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>某一品牌商品列表</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[brand_list][title]" name="SEO[brand_list][title]" value="{$currentSetting['brand_list']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[brand_list][keywords]" name="SEO[brand_list][keywords]" value="{$currentSetting['brand_list']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[brand_list][description]" name="SEO[brand_list][description]" value="{$currentSetting['brand_list']['description']|escape}" class="w300" type="text" /></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
        	<td colspan="2" ><input type="submit"  value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {form_open(admin_site_url('setting/seoset'),'name="form_credits"')}
    <span style="display:none" nctype="hide_tag"><a>{#sitename#}</a><a>{#name#}</a><a>{#key#}</a><a>{#description#}</a></span>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>积分中心</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[credits][title]" name="SEO[credits][title]" value="{$currentSetting['credits']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[credits][keywords]" name="SEO[credits][keywords]" value="{$currentSetting['credits']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[point][description]" name="SEO[credits][description]" value="{$currentSetting['credits']['description']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>积分中心商品内容</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[credits_content][title]" name="SEO[credits_content][title]" value="{$currentSetting['credits_content']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[credits_content][title]" name="SEO[credits_content][keywords]" value="{$currentSetting['credits_content']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[credits_content][title]" name="SEO[credits_content][description]" value="{$currentSetting['credits_content']['description']|escape}" class="w300" type="text" /></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit"  value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  *}
  {form_open(admin_site_url('setting/seoset'),'name="form_article"')}
    <span style="display:none" nctype="hide_tag"><a>{#sitename#}</a><a>{#article_class#}</a><a>{#name#}</a><a>{#key#}</a><a>{#description#}</a></span>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>文章分类列表</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[article][title]" name="SEO[article][title]" value="{$currentSetting['article']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[article][keywords]" name="SEO[article][keywords]" value="{$currentSetting['article']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[article][description]" name="SEO[article][description]" value="{$currentSetting['article']['description']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>文章内容</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[article_content][title]" name="SEO[article_content][title]" value="{$currentSetting['article_content']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[article_content][keywords]" name="SEO[article_content][keywords]" value="{$currentSetting['article_content']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[article_content][description]" name="SEO[article_content][description]" value="{$currentSetting['article_content']['description']|escape}" class="w300" type="text" /></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit"  value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {*
  {form_open(admin_site_url('setting/seoset'),'name="form_shop"')}
    <span style="display:none" nctype="hide_tag"><a>{#sitename#}</a><a>{#shopname#}</a><a>{#key#}</a><a>{#description#}</a></span>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>店铺</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[shop][title]" name="SEO[shop][title]" value="{$currentSetting['shop']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[shop][keywords]" name="SEO[shop][keywords]" value="{$currentSetting['shop']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[shop][description]" name="SEO[shop][description]" value="{$currentSetting['shop']['description']|escape}" class="w300" type="text" /></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  *}
  {form_open(admin_site_url('setting/seoset'),'name="form_product"')}
    <span style="display:none" nctype="hide_tag"><a>{#sitename#}</a><a>{#name#}</a><a>{#key#}</a><a>{#description#}</a></span>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>商品</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[product][title]" name="SEO[product][title]" value="{$currentSetting['product']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[product][keywords]" name="SEO[product][keywords]" value="{$currentSetting['product']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[product][desciption]" name="SEO[product][description]" value="{$currentSetting['product']['description']|escape}" class="w300" type="text" /></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {form_open(admin_site_url('setting/seoset'),'name="form_category"')}
    <span style="display:none" nctype="hide_tag"><a>{#sitename#}</a><a>{#name#}</a></span>
    <input type="hidden" name="form_name" value="category"/>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>商品分类</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">商品分类</td><td>
          	<select name="category" id="category">
	          <option value="">请选择...</option>
	          {foreach from=$goodsClassHTML item=item}
	          <option value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['gc_name']}</option>
	          {/foreach}
	        </select>
          </td>
        </tr>        
        <tr class="noborder">
          <td class="w96">title</td><td><input id="cate_title" name="cate_title" value="" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="cate_keywords" name="cate_keywords" value="" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="cate_description" name="cate_description" value="" class="w300" type="text" /></td>
        </tr>       
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {*
  {form_open(admin_site_url('setting/seoset'),'name="form_sns"')}
    <span style="display:none" nctype="hide_tag"><a>{#sitename#}</a><a>{#name#}</a></span>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>SNS</label></td>
        </tr>
        <tr class="noborder">
          <td class="w96">title</td><td><input id="SEO[sns][title]" name="SEO[sns][title]" value="{$currentSetting['sns']['title']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">keywords</td><td><input id="SEO[sns][keywords]" name="SEO[sns][keywords]" value="{$currentSetting['sns']['keywords']|escape}" class="w300" type="text" /></td>
        </tr>
        <tr class="noborder">
          <td class="w96">description</td><td><input id="SEO[sns][desciption]" name="SEO[sns][description]" value="{$currentSetting['sns']['description']|escape}" class="w300" type="text" /></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
  *}
	<div id="tag_tips">
	<span class="dialog_title">可用的代码，点击插入</span>
	<div style="margin: 0px; padding: 0px;line-height:25px;"></div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('.tab-base').find('a').bind('click',function(){
		$("#tag_tips").css('display','none');
		$('.tab-base').find('a').removeClass('current');
		$(this).addClass('current');
		$('form').css('display','none');
		$('form[name="form_'+$(this).attr('nctype')+'"]').css('display','');
		
		if ($(this).attr('nctype') == 'rewrite'){
			$('#prompt').css('display','none');
		}else{
			$('#prompt').css('display','');
		}
		
		$('span[nctype="hide_tag"]').find('a').css('padding-left','5px');
		$("#tag_tips>div").html($('form[name="form_'+$(this).attr('nctype')+'"]').find('span').html());
		$("#tag_tips").find('a').css('cursor','pointer');
		
		$("#tag_tips").find('a').bind('click',function(){
			var value = $(CUR_INPUT).val();
			if(value.indexOf($(this).html())<0 ){
				$(CUR_INPUT).val(value+$(this).html());
			}
		});
	});
	
	$('input[type="text"]').bind('focus',function(){
		CUR_INPUT = this;
		//定位弹出层的坐标
		var pos = $(this).position();
		var pos_x = pos.left+370;
		var pos_y = pos.top-20;
		$("#tag_tips").css({ 'left' : pos_x, 'top' : pos_y,'position' : 'absolute','display' : 'block' });
	});

	$('form').css('display','none');
	$('form[name="form_{$selectedGroup}"]').css('display','');
	$('.tab-base a[nctype="{$selectedGroup}"]').trigger("click");
	
	$('#category').bind('change',function(){
		$.getJSON("{admin_site_url('setting/ajax_category/')}?id=" +$(this).val(), function(json){
			if(json){
				$('#cate_title').val(json.data.gc_title);
				$('#cate_keywords').val(json.data.gc_keywords);
				$('#cate_description').val(json.data.gc_description);
			}else{
				$('#cate_title').val('');
				$('#cate_keywords').val('');
				$('#cate_description').val('');			
			}
		});
	});
	
	
	
	$('#toggmore').bind('click',function(){
		$('li[nctype="vmore"]').toggle();
	});
	
	$('li[nctype="vmore"]').hide();

});
</script>
<style type="text/css">
#tag_tips {
	padding:4px;
	border-radius: 2px 2px 2px 2px;
	box-shadow: 0 0 4px rgba(0, 0, 0, 0.75);
	display:none;
	padding: 4px;
	width:300px;
	z-index:9999;
	background-color:#FFFFFF;
}
.dialog_title {
    background-color: #F2F2F2;
    border-bottom: 1px solid #EAEAEA;
    color: #666666;
    display: block;
    font-weight: bold;
    line-height: 14px;
    padding: 5px;
}
</style>
{include file="common/main_footer.tpl"}