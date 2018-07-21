{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>导航管理</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('navigation/category')}"><span>管理</span></a></li>
      	<li><a {if empty($info['id'])}class="current"{/if} href="{admin_site_url('navigation/add')}"><span>新增</span></a></li>
      	{if $info['id']}<li><a class="current" href="{admin_site_url('navigation/edit/')}?id={$info['id']}"><span>编辑</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['id']}
  {form_open(admin_site_url('navigation/edit'),'id="article_class_form"')}
  {else}
  {form_open(admin_site_url('navigation/add'),'id="article_class_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>导航类型</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              <ul class="nofloat">
              <li class="left w100pre"><span class="radio">
                <input type="radio"  value="0" name="nav_type" id="diy" onclick="showType('diy');">
                <label for="diy">自定义导航</label>
                </span></li>
              <li class="left w100pre"><span class="radio">
                <input type="radio"  value="1" name="nav_type" id="goods_class" onclick="showType('goods_class');">
                <label for="goods_class">商品分类</label>
                </span>
                <select name="goods_class_id" id="goods_class_id" style="display: none;">
                      <option value="">请选择</option>
                      <option value="0">全部分类</option>
		              {foreach from=$goodsClass item=item}
		              <option {if $info['item_id'] == $item['gc_id']}selected{/if} value="{$item['gc_id']}">{str_repeat('&nbsp;&nbsp;',$item['level'])}{$item['level']+1} {$item['name']}</option>
		              {/foreach}
                 </select>
              </li>
              <li class="left w100pre"><span class="radio">
                <input type="radio"  value="2" name="nav_type" id="article_class" onclick="showType('article_class');">
                <label for="article_class">文章分类</label>
                </span>
                <select name="article_class_id" id="article_class_id" style="display: none;">
                    <option value="">请选择</option>
                    <option value="0">全部分类</option>
                    {foreach from=$articleClass item=item}
                    <option {if $info['item_id'] == $item['ac_id']}selected{/if} value="{$item['ac_id']}">{str_repeat('&nbsp;&nbsp;',$item['level'])}{$item['level']+1} {$item['name']}</option>
                    {/foreach}
                </select>
              </li>
              <li class="left w100pre"><span class="radio">
                <input type="radio"  value="3" name="nav_type" id="article" onclick="showType('article');">
                <label for="article">文章</label>
                </span>
                <input type="text" id="article_id"  name="article_id" value="" placeholder="请输入文章ID" style="display: none;"/>
              </li>
              <li class="left w100pre"><span class="radio">
                <input type="radio"  value="4" name="nav_type" id="cms_article_class" onclick="showType('cms_article_class');">
                <label for="cms_article_class">CMS文章分类</label>
                </span>
                <select name="cms_article_class_id" id="cms_article_class_id" style="display: none;">
                    <option value="">请选择</option>
                    <option value="0">全部分类</option>
                    {foreach from=$cmsArticleClass item=item}
                    <option {if $info['item_id'] == $item['id']}selected{/if} value="{$item['id']}">{str_repeat('&nbsp;&nbsp;',$item['level'])}{$item['level']+1} {$item['name']}</option>
                    {/foreach}
                </select>
              </li>
              <li class="left w100pre"><span class="radio">
                <input type="radio"  value="5" name="nav_type" id="cms_article" onclick="showType('cms_article');">
                <label for="article">CMS文章</label>
                </span>
                <input type="text" id="cms_article_id"  name="cms_article_id" value="" placeholder="请输入CMS文章ID" style="display: none;"/>
              </li>
              {*
              <li class="left w100pre"><span class="radio">
                <input type="radio"  value="4" name="nav_type" id="activity" onclick="showType('activity');">
                <label for="activity">活动</label>
                </span>
                <select name="activity_id" id="activity_id" style="display: none;">
                </select>
              </li>
              *}
            </ul>
            </td>
           <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">导航名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="pid">上级导航:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="pid">
	          <option value="">请选择...</option>
	          {foreach from=$list item=item}
	          <option {if $info['pid'] == $item['id']}selected{/if} value="{$item['id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('pid')}如果选择父级导航，那么新增的分类则为被选择上级导航的子导航</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="url">导航链接:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['url']|escape}" name="url" id="url" class="txt url"></td>
          <td class="vatop tips">{form_error('url')}<span class="tips">{literal}{ID}{/literal} 表示当即记录的ID 对于某些需要在显示页面中自动展示当前导航子导航的页面需要插入该值</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>
            <label for="type">所在位置:</label>
            </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul>
             {*
              <li>
                <label><input type="radio" value="0" {if $info['nav_location'] == 0}checked="checked"{/if} name="nav_location">顶部</label>
              </li>
              *}
              <li>
                <label><input type="radio" {if $info['nav_location'] == 1}checked="checked"{/if} value="1" name="nav_location">主导航</label>
              </li>
              <li>
                <label><input type="radio" {if $info['nav_location'] == 2}checked="checked"{/if} value="2" name="nav_location">底部</label>
              </li>
            </ul></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="url_en">跳转方式:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><select name="jump_type">
            <option value="0" {if $info['jump_type'] == 0}selected{/if}>当前窗口</option>
            <option value="1" {if $info['jump_type'] == 1}selected{/if}>新窗口</option>
          </select></td>
          <td class="vatop tips">{form_error('jump_type')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['displayorder']}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script>
    function showType(type){
	    $('#goods_class_id').css('display','none');
	    $('#article_class_id').css('display','none');
	    $('#cms_article_class_id').css('display','none');
	    $('#article_id').css('display','none');
	    $('#cms_article_id').css('display','none');
	    
	    if(type == 'diy'){
	        $('input.url').attr('disabled',false);
	    }else{
	        //$('input.url').attr('disabled',true);
	        $('#'+type+'_id').show();   
	    }
	}
	
	$(function(){
	   var setInfo = function(json){
           $("#name").val(json.data.name);
           $("#url").val(json.data.url);
       }
	   
	   $("#goods_class_id").bind("change",function(){
	       $.getJSON("{admin_site_url('goods_class/getNavUrl')}",{ gc_id: $(this).val() },setInfo )
	   });
	   
	   $("#article_class_id").bind("change",function(){
           $.getJSON("{admin_site_url('article_class/getNavUrl')}",{ ac_id: $(this).val() }, setInfo)
       });
       
       $("#article_id").bind("blur",function(){
           $.getJSON("{admin_site_url('article/getNavUrl')}",{ id: $(this).val() }, setInfo)
       });
       
       $("#cms_article_class_id").bind("change",function(){
           $.getJSON("{admin_site_url('cms_article_class/getNavUrl')}",{ id: $(this).val() }, setInfo)
       });
       
       $("#cms_article_id").bind("blur",function(){
           $.getJSON("{admin_site_url('cms_article/getNavUrl')}",{ id: $(this).val() }, setInfo)
       });
       
	
	});
	
  </script>
{include file="common/main_footer.tpl"}