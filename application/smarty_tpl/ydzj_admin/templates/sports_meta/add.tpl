{include file="common/main_header.tpl"}
{config_load file="sport.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#meta_title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('sports_meta/index')}"><span>{#manage#}</span></a></li>
      	<li><a class="current"><span>{if $info['id']}编辑{else}新增{/if}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['id']}
  {form_open(admin_site_url('sports_meta/edit'),'id="add_form"')}
  {else}
  {form_open(admin_site_url('sports_meta/add'),'id="add_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#title#}名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">开启状态: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="status1" {if $info['status'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="status0" {if $info['status'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="status1" name="status" {if $info['status'] == 1}checked{/if} value="1" type="radio">
            <input id="status0" name="status" {if $info['status'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('status')} 分类启动状态。</td>
        </tr>
        <tr>
          <td colspan="2" class="required">参与形势: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
          	<select name="teamwork">
          		<option value="1" {if $info['teamwork'] == 1}selected{/if}>团体</option>
          		<option value="2" {if $info['teamwork'] == 2}selected{/if}>团体或个人</option>
          		<option value="3" {if $info['teamwork'] == 3}selected{/if}>个人</option>
          	</select>
          </td>
          <td class="vatop tips">{form_error('teamwork')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['cate_sort']}{$info['cate_sort']}{else}255{/if}" name="cate_sort" id="cate_sort" class="txt"></td>
          <td class="vatop tips">{form_error('cate_sort')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	$(function(){
		$("#sports_logo").change(function(){
			$("#logo_show_url").val($(this).val());
		});
	})
 </script>
{include file="common/main_footer.tpl"}