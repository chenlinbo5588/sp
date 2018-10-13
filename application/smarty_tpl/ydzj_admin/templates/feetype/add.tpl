{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2">
      <tbody>
         <tr class="noborder">
          <td colspan="2"><label class="validation">{#resident_name#}:</label>{form_error('resident_id')}</td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
	          	<ul class="ulListStyle1 clearfix">
	          	{foreach from=$residentList item=item}
	          		<li {if $info['resident_id'] == $item['id']}class="selected"{/if}><label><input type="radio" name="resident_id" value="{$item['id']}" {if $info['resident_id'] == $item['id']}checked="checked"{/if}/><span>{$item['name']|escape}</span></label></li>
	          	{/foreach}
	          	</ul>
	         </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">费用名称</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
				<input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt">
          </td>

          <td class="vatop tips"><label id="error_fee_id"></label>{form_error('name')}</td>
        </tr>
        
    	<tr class="noborder">
          <td colspan="2"><label class="validation" for="year">{#year#}: </label></td>
        </tr>
   		<tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['year']|escape}" name="year" id="year" class="txt">
          </td>
          <td class="vatop tips"><label id="error_fee_year"></label>{form_error('year')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation">费用配置: </label></td>
        </tr>
        <tr>
        	<td colspan="2">
        		<table id="feeConfigTable">
        			<thead>
        				<tr>
        					<th>费用类型</th>
        					<th>单价</th>
        					<th>物业类型</th>
        					<th>计费方式</th>
        					<th>计算公式</th>
        					<th>操作</th>
        				</tr>
        			</thead>
        			<tbody>
        				
        				<tr>
        					<td>
        						<select name="feeName[]">
						          <option value="">请选择...</option>
						          {foreach from=$feeNameList item=item}
						          <option {if $fee_rule[0]['feeName'] == $item['show_name']}selected{/if}  value="{$item['show_name']}">{$item['show_name']}</option>
						          {/foreach}
						        </select>
        					</td>
        					<td>
        						<input type="text" name="price[]" value="{$fee_rule[0]['price']|escape}"/>
        					</td>
        					<td>
        						<select name="wuyeType[]">
						          <option value="">请选择...</option>
						          {foreach from=$wuyeTypeList item=item}
						          <option {if $fee_rule[0]['wuyeType'] == $item['show_name']}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
						          {/foreach}
						        </select>
        					</td>
        					<td>
        						<select name="billingStyle[]">
						          <option value="">请选择...</option>
						          {foreach from=$billingStyleList item=item}
						          <option {if $fee_rule[0]['billingStyle'] == $item['show_name']}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
						          {/foreach}
						        </select>
        					</td>
        					<td>
        						<input type="text" name="cale[]" value="{$fee_rule[0]['cale']|escape}"/>
        					</td>
        					<td>
        						<input type="button" class="dynBtn" name="addBtn" value="添加"/>
        					</td>
        					 <td class="vatop tips">{form_error('displayorder')} 当费用类型不为物业费时，物业类型只能选择普通</td>
        				</tr>
        				{if $fee_rule}
						  {foreach from=$fee_rule key=key item=valus}
						  	{if $key>0}
		        				<tr>
		        					<td>
		        						<select name="feeName[]">
								          <option value="">请选择...</option>
								          {foreach from=$feeNameList item=item}
								          <option {if $valus['feeName'] == $item['show_name']}selected{/if}  value="{$item['show_name']}">{$item['show_name']}</option>
								          {/foreach}
								        </select>
		        					</td>
		        					<td>
		        						<input type="text" name="price[]" value="{$valus['price']|escape}"/>
		        					</td>
		        					<td>
		        						<select name="wuyeType[]">
								          <option value="">请选择...</option>
								          {foreach from=$wuyeTypeList item=item}
								          <option {if $valus['wuyeType'] == $item['show_name']}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
								          {/foreach}
								        </select>
		        					</td>
		        					<td>
		        						<select name="billingStyle[]">
								          <option value="">请选择...</option>
								          {foreach from=$billingStyleList item=item}
								          <option {if $valus['billingStyle'] == $item['show_name']}selected{/if} value="{$item['show_name']}">{$item['show_name']}</option>
								          {/foreach}
								        </select>
		        					</td>
		        					<td>
		        						<input type="text" name="cale[]" value="{$valus['cale']|escape}"/>
		        					</td>
									<td>
										<input type="button" class="dynBtn" name="deleteBtn" value="删除"/>
									</td>
								</tr>
							{/if}
						  {/foreach}
						{/if}
        				
        			</tbody>
        		</table>
        		
        	</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['displayorder']}{$info['displayorder']}{else}255{/if}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
        <tr>
        <td>
	      	<ul class="ulListStyle1 clearfix">
			<li {if $info['generate_plan'] == 1}class="selected"{/if}><label><input type="checkbox" name="generate_plan" value="1" {if $info['generate_plan'] == 1} checked="checked"{/if}/>生成计划</label></li>
			<li {if $info['generate_deatil'] == 1}class="selected"{/if}><label><input type="checkbox" name="generate_deatil" value="1" {if $info['generate_deatil'] == 1} checked="checked"{/if}/>生成明细</label></li>
 			</ul>
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
  

  
  <script type="x-template" id="templateRow">
		<tr>
			<td>
				<select name="feeName[]">
		          <option value="">请选择...</option>
		          {foreach from=$feeNameList item=item}
		          <option value="{$item['show_name']}">{$item['show_name']}</option>
		          {/foreach}
		        </select>
			</td>
			<td>
				<input type="text" name="price[]" value=""/>
			</td>
			<td>
				<select name="wuyeType[]">
		          <option value="">请选择...</option>
		          {foreach from=$wuyeTypeList item=item}
		          <option value="{$item['show_name']}">{$item['show_name']}</option>
		          {/foreach}
		        </select>
			</td>
			<td>
				<select name="billingStyle[]">
		          <option value="">请选择...</option>
		          {foreach from=$billingStyleList item=item}
		          <option value="{$item['show_name']}">{$item['show_name']}</option>
		          {/foreach}
		        </select>
			</td>
			<td>
				<input type="text" name="cale[]" value=""/>
			</td>
			<td>
				<input type="button" class="dynBtn" name="deleteBtn" value="删除"/>
			</td>
		</tr>
  </script>
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")];
  </script>
  <script type="text/javascript" src="{resource_url('js/service/feetype_add.js',true)}"></script>
{include file="common/main_footer.tpl"}