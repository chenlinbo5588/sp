        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="id_type">{#id_type#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="id_type" id="id_type">
	          <option value="">请选择...</option>
	          {foreach from=$idTypeList item=item}
	          <option {if $info['id_type'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('id_type')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="id_no">{#id_no#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['id_no']|escape}" name="id_no" id="id_no" class="txt"></td>
          <td class="vatop tips">{form_error('id_no')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="sex">{#sex#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="sex">
          		<option value="2" {if $info['sex'] == 2}selected{/if}>女</option>
          		<option value="1" {if $info['sex'] == 1}selected{/if}>男</option>
          	</select>
          </td>
          <td class="vatop tips">{form_error('sex')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="age">{#age#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['age']}" name="age" id="age" class="txt"></td>
          <td class="vatop tips">{form_error('age')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="birthday">{#birthday#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" class="datepicker" value="{$info['birthday']|escape}" name="birthday" id="birthday" class="txt"></td>
          <td class="vatop tips">{form_error('birthday')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="marriage">{#marriage#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="marriage" id="marriage">
	          <option value="">请选择...</option>
	          {foreach from=$marriageList item=item}
	          <option {if $info['marriage'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('marriage')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="jiguan">{#jiguan#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="jiguan" id="jiguan">
	          <option value="">请选择...</option>
	          {foreach from=$jiguanList item=item}
	          <option {if $info['jiguan'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']|escape}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('jiguan')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="mobile">{#mobile#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']}" name="mobile" id="mobile" class="txt"></td>
          <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="address">{#address#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips">{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="degree">{#degree#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="degree" id="degree">
	          <option value="">请选择...</option>
	          {foreach from=$xueliList item=item}
	          <option {if $info['degree'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('degree')}</td>
        </tr>