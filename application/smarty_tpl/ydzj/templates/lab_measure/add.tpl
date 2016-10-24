{include file="common/my_header.tpl"}
    {config_load file="goods.conf"}
    {include file="./measure_common.tpl"}
	<div class="fixed-empty"></div>
    <div class="feedback">{$feedback}</div>
	  {if $info['id']}
	  <form name="categoryForm" method="post" action="{site_url('lab_measure/edit')}">
	  <input type="hidden" name="id" value="{$info['id']}"/>
	  {else}
	  <form name="categoryForm" method="post" action="{site_url('lab_measure/add')}">
	  {/if}
	  
	  <table class="autotable">
       <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="address">{#measure_title#}名称:</label><label class="errtip" id="error_name"></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" class="txt" name="name" value="{$info['name']|escape}" placeholder="请输入类别名称" /><input type="submit" class="msbtn" value="保存" /></td>
          <td class="vatop tips"><label class="errtip" id="error_address"></label></td>
        </tr>
       </tbody>
      </table>
      <script>
      	$(function(){
      		{include file="common/form_ajax_submit.tpl"}
      	});
      </script>
{include file="common/my_footer.tpl"}