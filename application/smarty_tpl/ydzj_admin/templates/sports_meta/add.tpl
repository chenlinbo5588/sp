{include file="common/main_header.tpl"}
{config_load file="sport.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#meta_title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('sports_meta/index')}"><span>{#manage#}</span></a></li>
      	<li><a href="{admin_site_url('sports_meta/add')}" {if !$info['id']}class="current"{/if}><span>新增</span></a></li>
      	{if $info['id']}<li><a href="{admin_site_url('sports_meta/index?id=')}{$info['id']}" class="current"><span>编辑</span></a></li>{/if}
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
      		<td colspan="2" class="required"><label class="validation" for="category_name">{#cate_title#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	<select name="category_name" id="category_name">
	          	<option value="">请选择</option>
	            {foreach from=$cateList item=item}
	            <option value="{$item['name']}" {if $info['category_name'] == $item['name']}selected{/if}>{$item['name']}</option>
	            {/foreach}
	        	</select>
	        </td>
	        <td class="vatop tips">{form_error('category_name')} <span>没有找到{#cate_title#} 点击去增加</span><a href="{admin_site_url('sports_cate/add')}">{#cate_title#}</a></td>
        </tr>
        <tr class="noborder">
      		<td colspan="2" class="required"><label for="gname">{#groupname#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	<select name="gname">
	          	{foreach from=$groupList item=item}
	          		<option value="{$item['gname']|escape}" {if $item['gname'] == $info['gname']}selected{/if}">{$item['gname']|escape}</option>
	          	{/foreach}
	          	</select>
	        </td>
	        <td class="vatop tips">{form_error('gname')}</td>
        </tr>
        {if empty($info['id'])}
        <tr class="noborder">
      		<td colspan="2" class="required"><label for="newgname">新{#groupname#}</label></td>
      	</tr>
      	<tr class="noborder">
	        <td class="vatop rowform">
	          	<input type="text" class="txt" value="{$info['newgname']|escape}" name="newgname" id="newgname" placeholder="请输入新的{#groupname#}"  maxlength="20" class="txt">
	        </td>
	        <td class="vatop tips">{form_error('newgname')}</td>
        </tr>
        {/if}
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#meta_title#}名称:</label></td>
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
          <td class="vatop tips">{form_error('status')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['meta_sort']}{$info['meta_sort']}{else}255{/if}" name="meta_sort" id="meta_sort" class="txt"></td>
          <td class="vatop tips">{form_error('meta_sort')} 数字范围为0~255，数字越小越靠前</td>
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
  $(function(){
  	$("select[name=category_name]").change(function(){
  		var currentCate = $(this).val();
  		$.get("{admin_site_url('sports_meta/getgroup')}", { category_name: currentCate , ts: Math.random() } ,function(json){
  			if(json.message == 'success'){
  				$("select[name=gname").html('<option value="">请选择</option>' );
  				for(var i = 0; i < json['data'].length; i++) {
  					
  					$("select[name=gname").append('<option value="' + json['data'][i].gname + '">' + json['data'][i].gname + '</option>'); 
  				}
  			}
  		
  		})
  	});
  	
  	
  })
  </script>
{include file="common/main_footer.tpl"}