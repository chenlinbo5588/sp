{include file="common/main_header_navs.tpl"}
  {config_load file="user.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
</body>

  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2">
      <tbody>
      <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">推荐名称</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
           <td class="vatop tips">推荐名称按推荐位置命名比如在小程序的新闻首页显示：小程序-新闻首页</td>
        </tr>
        
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="style">推荐形式</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="style">
	          <option value="">请选择...</option>
	          {foreach from=$styleList key=key item=item}
	          <option {if $info['style'] == $item['id']}selected{/if} value="{$item['id']}">{$key}</option>
	          {/foreach}
	        </select>
          </td>
        </tr>
        

        
 	 	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="show_number">显示条数</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['show_number']|escape}" name="show_number" id="show_number" class="txt"></td>
        </tr>
        
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="dateformat">时间格式</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="dateformat">
	          <option value="">请选择...</option>
	          {foreach from=$dataformatList key=key item=item}
	          <option {if $info['dateformat'] == $item['id']}selected{/if} value="{$item['id']}">{$key}</option>
	          {/foreach}
	        </select>
          </td>
        </tr>
        
 	 	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="cachetime">缓存时间(分钟)</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['cachetime']|escape}" name="cachetime" id="cachetime" class="txt"></td>
        </tr>
        
 	 	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="cachetime">标题最大长度</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['max_title']|escape}" name="max_title" id="max_title" class="txt"></td>
        </tr>
        
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">模板名称</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="template_id">
	          <option value="">请选择...</option>
	          {foreach from=$mouldList key=key item=item}
	          <option {if $info['template_id'] == $item['id']}selected{/if} value="{$item['id']}">{$item['name']}</option>
	          {/foreach}
	        </select>
          </td>
        </tr>
        
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="submit" name="tijiao" value="保存" class="msbtn"/>
          	{if $gobackUrl}
	    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
	    	{/if}
          </td>
        </tr>
      </tbody>
    </table>
	
	
	
	
  </form>
  <script type="text/javascript">
	submitUrl = [new RegExp("{$uri_string}")];
  </script>
  <script>
	  $(function(){
		  	$( ".datepicker" ).datepicker();
			
			$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
			bindAjaxSubmit("#infoform");
			
		});
	</script>
{include file="common/main_footer.tpl"}