{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
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
          <td colspan="2"><label class="validation">所在{#resident_name#}:</label><label class="errtip" id="error_resident_id"></label>{form_error('resident_id')}</td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
	          	<ul class="ulListStyle1 clearfix">
	          	{foreach from=$residentList item=item}
	          		<li {if $info['resident_id'] == $item['id']}class="selected"{/if}><label><input type="radio" name="resident_id" {if $info['resident_id'] == $item['id']}checked="checked"{/if} value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
	          	{/foreach}
	          	</ul>
	         </td>
        </tr>
		<tr class="noborder">
          <td colspan="2"><label class="validation" for="name">{#yezhu_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="id_type">{#id_type#}:</label></td>
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
          <td colspan="2"><label class="validation" for="id_no">{#id_no#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['id_no']|escape}" name="id_no" id="id_no" class="txt"></td>
          <td class="vatop tips">{form_error('id_no')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="sex">{#sex#}:</label></td>
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
          <td colspan="2"><label class="validation" for="age">{#age#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['age']}" name="age" id="age" class="txt"></td>
          <td class="vatop tips">{form_error('age')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="birthday">{#birthday#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" class="datepicker" value="{$info['birthday']|escape}" name="birthday" id="birthday" class="txt"></td>
          <td class="vatop tips">{form_error('birthday')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation" for="jiguan">{#jiguan#}:</label></td>
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
          <td colspan="2"><label class="validation" for="mobile">{#mobile#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
          <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="car_no1">{#car_no#}1:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['car_no1']|escape}" name="car_no1" id="car_no1" class="txt"></td>
          <td class="vatop tips">{form_error('car_no1')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="car_no1">{#car_no#}2:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['car_no2']|escape}" name="car_no2" id="car_no2" class="txt"></td>
          <td class="vatop tips">{form_error('car_no2')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label for="car_no1">{#car_no#}3:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['car_no3']|escape}" name="car_no3" id="car_no3" class="txt"></td>
          <td class="vatop tips">{form_error('car_no3')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2">家庭成员: </td>
        </tr>
        <tr>
	    	<td colspan="2">
	    	{if $familyList}
	    		<table id="familyConfigTable">
	    			<thead>
	    				<tr>
	    					<th>联系电话</th>
	    					<th>姓名</th>
	    				</tr>
	    			</thead>
	    			<tbody>
						  {foreach from=$familyList key=key item=valus}
        				<tr>
	    					<td>{$valus['mobile']|escape}</td>
	    					<td>{$valus['name']|escape}</td>
						</tr>
						  {/foreach}
					</tbody>
				</table>
			{/if}
			</td>
        </tr>
        <tr>
			<td>
	    	{if $houseList}
	    		<table id="houseConfigTable">
	    			<thead>
	    				<tr>
	    					<th>物业地址</th>
	    				</tr>
	    			</thead>
	    			<tbody>
						  {foreach from=$houseList key=key item=valus}
        				<tr>
	    					<td>{$valus['address']|escape}</td>
						</tr>
						  {/foreach}
					</tbody>
				</table>
			{/if}
			</td>
			</tr>
        	<tr>
			<td>
	    	{if $parkibgList}
	    		<table id="parkingConfigTable">
	    			<thead>
	    				<tr>
	    					<th>车位名称</th>
	    				</tr>
	    			</thead>
	    			<tbody>
						  {foreach from=$parkibgList key=key item=valus}
        				<tr>
	    					<td>{$valus['name']|escape}</td>
						</tr>
						  {/foreach}
					</tbody>
				</table>
			{/if}
			</td>
			</tr>
        <tr>
          <td colspan="2"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['displayorder']}{$info['displayorder']}{else}255{/if}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')} 数字范围为0~255，数字越小越靠前</td>
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
  <script type="text/javascript" src="{resource_url('js/navigation.js',true)}">   </script>
  <script type="text/javascript">
  	var province_idcard = {$province_idcard},submitUrl = [new RegExp("{$uri_string}")];
  </script>
  <script type="text/javascript" src="{resource_url('js/service/yezhu.js',true)}"></script>
{include file="common/main_footer.tpl"}