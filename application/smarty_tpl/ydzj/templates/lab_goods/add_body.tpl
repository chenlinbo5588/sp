    {config_load file="lab_goods.conf"}
    <table class="fulltable">
      <tbody>
      	<tr class="noborder">
          <td class="required w200"><label class="validation">{#lab_name#}:</label></td>
          <td class="vatop rowform">
          	{if $action == 'add'}
	        <select name="lab_id">
	            <option value="">请选择</option>
	            {foreach from=$labList item=item}
	            <option value="{$item['id']}" {if $info['lab_id'] == $item['id']}selected{/if}>{str_repeat('----',$item['level'])}{$item['name']|escape}</option>
	            {/foreach}
	        </select>
	        {else}
	        	{$info['lab_name']|escape}
	        {/if}
             <label class="errtip" id="error_lab_id">{form_error('lab_id')}</label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">{#code#}:</label></td>
          <td class="vatop rowform"><input type="text" value="{$info['code']|escape}" name="code" class="txt"  placeholder="请输入{#code#}">
            <span class="tips">如：A号药品柜第二层</span> <label class="errtip" id="error_code"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">{#category_name#}:</label><label class="errtip" id="error_category_id"></label></td>
          <td class="vatop rowform">
	          <select name="category_id">
	              {foreach from=$categoryList item=item key=key}
	              <option value="{$key}" {if $key == $info['category_id']}selected{/if}>{$item['sep']}{$item['name']|escape}</option>
	              {/foreach}
	          </select>
              <span class="hightlight">如A柜1层 </span><span class="tip">如果找不到到对应类别,<a href="{site_url('lab_gcate/add')}">点击添加{#category_title#}</a></span>
           </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">{#goods_name#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="name"  value="{$info['name']|escape}" placeholder="请输入{#goods_name#}" />
             <label class="errtip" id="error_name"></label>
           </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">{#measure_title#}:</label></td>
          <td class="vatop rowform">
          	<select class="form_select" name="measure">
              {foreach from=$measureList item=item key=key}
              <option value="{$item['name']}" {if $item['name'] == $info['measure']}selected{/if}>{$item['name']|escape}</option>
              {/foreach}
           </select>
          <label class="errtip" id="error_measure"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label>{#specific#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="specific"  value="{$info['specific']|escape}" placeholder="请输入{#specific#}" />
             <label class="errtip" id="error_specific"></label>
           </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label>{#cas#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="cas"  value="{$info['cas']|escape}" placeholder="请输入{#cas#}" />
            <label class="errtip" id="error_cas"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label>{#danger_remark#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="danger_remark"  value="{$info['danger_remark']|escape}" placeholder="请输入{#danger_remark#}" />
            <span class="tip">例如: 剧毒品，易制毒，易爆，易腐蚀</span> <label class="errtip" id="error_danger_remark"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label>{#manufacturer#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="manufacturer"  value="{$info['manufacturer']|escape}" placeholder="请输入{#manufacturer#}" />
            <label class="errtip" id="error_manufacturer"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">{#price#}(元):</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="price"  value="{$info['price']|escape}" placeholder="请输入{#price#}" />
            <label class="errtip" id="error_price"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">{#quantity#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="quantity"  value="{$info['quantity']|escape}" placeholder="请输入{#quantity#}" />
            <label class="errtip" id="error_quantity"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label>{#threshold#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="threshold"  value="{$info['threshold']|escape}" placeholder="请输入{#quantity#}" />
            <span class="vatop tips">(0为不报警)</span><label class="errtip" id="error_threshold"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label>{#subject_name#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="subject_name"  value="{$info['subject_name']|escape}" placeholder="请输入{#subject_name#}" />
             <label class="errtip" id="error_subject_name"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label>{#remark#}:</label></td>
          <td class="vatop rowform">
          	<input type="text" class="txt" name="remark"  value="{$info['remark']|escape}" placeholder="请输入{#remark#}" />
            <label class="errtip" id="error_remark"></label>
          </td>
        </tr>
        <tr>
           <td></td>
          <td>{if $action != 'info'}<input type="submit" name="submit" value="保存" class="master_btn"/>{/if}</td>
        </tr>
       </tbody>
    </table>