{include file="common/main_header.tpl"}
  {include file="common/sub_nav.tpl"}
  {if $info['id']}
  {form_open(admin_site_url($moduleClassName|cat:'/edit?id='|cat:$info['id']),'id="add_form"')}
  {else}
  {form_open(admin_site_url($moduleClassName|cat:'/add'),'id="add_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
  	<input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="show_name">名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['show_name']|escape}" name="show_name" id="show_name" class="txt"></td>
          <td class="vatop tips">{form_error('show_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="pid">上级分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <select name="pid">
              <option value="">请选择...</option>
              {foreach from=$list item=item}
              <option {if $info['pid'] == $item['id']}selected{/if} value="{$item['id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['show_name']|escape}</option>
              {/foreach}
            </select>
          </td>
          <td class="vatop tips">{form_error('pid')}如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">开启状态: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="enable1" {if $info['enable'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="enable0" {if $info['enable'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="enable1" name="enable" {if $info['enable'] == 1}checked{/if} value="1" type="radio">
            <input id="enable0" name="enable" {if $info['enable'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('enable')}</td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['displayorder']}{$info['displayorder']}{else}255{/if}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/>&nbsp;{if $gobackUrl}<a class="salvebtn" href="{$gobackUrl}">返回</a>{/if}</td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script>
		{if $redirectUrl}
			setTimeout(function(){
				location.href="{$redirectUrl}";
			},1000);
		{/if}
  </script>
{include file="common/main_footer.tpl"}