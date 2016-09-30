    {config_load file="goods.conf"}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#address#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	{if $action == 'add'}
	        <select name="lab_id">
	            <option value="">请选择</option>
	            {foreach from=$labList item=item}
	            <option value="{$item['id']}" {if $info['lab_id'] == $item['id']}selected{/if}>{str_repeat('----',$item['level'])}{$item['address']|escape}</option>
	            {/foreach}
	        </select>
	        {else}
	        	{$info['lab_address']|escape}
	        {/if}
          </td>
          <td class="vatop tips"><label class="errtip" id="error_lab_id">{form_error('lab_id')}</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#code#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['code']|escape}" name="code" class="txt"  placeholder="请输入{#code#}"></td>
          <td class="vatop tips">如：A号药品柜第二层 <label class="errtip" id="error_code"></label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#category_name#}:</label><label class="errtip" id="error_category_id"></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
	          <select name="category_id">
	              {foreach from=$categoryList item=item key=key}
	              <option value="{$key}" {if $key == $info['category_id']}selected{/if}>{$item['sep']}{$item['name']|escape}</option>
	              {/foreach}
	          </select>
          </td>
          <td class="vatop tips"><span class="hightlight">如A柜1层 </span><span class="tip">如果找不到到对应类别,<a href="{site_url('goods_category/add')}">点击添加{#category_title#}</a></span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#goods_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="name"  value="{$info['name']|escape}" placeholder="请输入{#goods_name#}" />
          </td>
          <td class="vatop tips"><label class="errtip" id="error_name"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#measure_title#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select class="form_select" name="measure">
              {foreach from=$measureList item=item key=key}
              <option value="{$item['name']}" {if $item['name'] == $info['measure']}selected{/if}>{$item['name']|escape}</option>
              {/foreach}
           </select>
          </td>
          <td class="vatop tips"><label class="errtip" id="error_measure"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#specific#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="specific"  value="{$info['specific']|escape}" placeholder="请输入{#specific#}" />
          </td>
          <td class="vatop tips"><label class="errtip" id="error_specific"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#cas#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="cas"  value="{$info['cas']|escape}" placeholder="请输入{#cas#}" />
          </td>
          <td class="vatop tips"><label class="errtip" id="error_cas"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#danger_remark#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="danger_remark"  value="{$info['danger_remark']|escape}" placeholder="请输入{#danger_remark#}" />
          </td>
          <td class="vatop tips">例如: 剧毒品，易制毒，易爆，易腐蚀 <label class="errtip" id="error_danger_remark"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#manufacturer#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="manufacturer"  value="{$info['manufacturer']|escape}" placeholder="请输入{#manufacturer#}" />
          </td>
          <td class="vatop tips"><label class="errtip" id="error_manufacturer"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#price#}(元):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="price"  value="{$info['price']|escape}" placeholder="请输入{#price#}" />
          </td>
          <td class="vatop tips"><label class="errtip" id="error_price"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#quantity#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="quantity"  value="{$info['quantity']|escape}" placeholder="请输入{#quantity#}" />
          </td>
          <td class="vatop tips"><label class="errtip" id="error_quantity"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#threshold#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="threshold"  value="{$info['threshold']|escape}" placeholder="请输入{#quantity#}" />
          </td>
          <td class="vatop tips">(0为不报警) <label class="errtip" id="error_threshold"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#subject_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="subject_name"  value="{$info['subject_name']|escape}" placeholder="请输入{#subject_name#}" />
          </td>
          <td class="vatop tips"><label class="errtip" id="error_subject_name"></label> </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#project_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" class="txt" name="project_name"  value="{$info['project_name']|escape}" placeholder="请输入{#project_name#}" />
          </td>
          <td class="vatop tips"><label class="errtip" id="error_project_name"></label> </td>
        </tr>
       </tbody>
       <tfoot>
        <tr>
          <td colspan="2">{if $action != 'info'}<input type="submit" name="submit" value="保存" class="msbtn"/>{/if}</td>
        </tr>
      </tfoot>
    </table>