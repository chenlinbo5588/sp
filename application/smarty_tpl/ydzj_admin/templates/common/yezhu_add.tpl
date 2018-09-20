  <tr class="noborder">
          <td colspan="2" class="required">{#yezhu_name#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text"  value="{$info['yezhu_name']|escape}" readonly name="yuan_yezhu_name" id="yuan_yezhu_name" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip"   id="error_yuan_yezhu_name"></label>{form_error('yuan_yezhu_name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#mobile#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['mobile']|escape}"} readonly name="old_mobile" id="old_mobile" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip"  id="error_old_mobile"></label>{form_error('old_mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="mobile">家庭成员{#mobile#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="" name="mobile" id="mobile" class="txt">
          </td>
          <td class="vatop tips"><label class="errtip"  id="error_mobile"></label>{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="yezhu_name">家庭成员{#yezhu_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text"  value="" name="yezhu_name" id="yezhu_name" class="txt"></td>
          <td class="vatop tips">{form_error('yezhu_name')}</td>
        </tr>