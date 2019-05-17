{include file="common/main_header_navs.tpl"}
  {config_load file="user.conf"}
  {if $info['uid']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['uid']),'uid="infoform"')}
  <input type="hidden" name="uid" value="{$info['uid']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  {/if}
</body>

  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2">
      <tbody>
         
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="mobile">{#mobile#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>

        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#name#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>

        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#company_name#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['company_name']|escape}" name="company_name" id="company_name" class="txt"></td>
          <td class="vatop tips"><label id="error_company_name"></label>{form_error('company_name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">{#group_name#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="group_name">
	          <option value="">请选择...</option>
	          {foreach from=$groupList key=key item=item}
	          <option {if $info['group_name'] == $key}selected{/if} value="{$key}">{$key}</option>
	          {/foreach}
	        </select>
          </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="user_type">{#user_type#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="user_type">
	          <option value="">请选择...</option>
	          {foreach from=$userType key=key item=item}
	          <option {if $info['user_type'] == $key}selected{/if} value="{$key}">{$item}</option>
	          {/foreach}
	        </select>
          </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="user_status">{#user_status#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="user_status1" {if $info['user_status'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if} ><span>开启</span></label>
            <label for="site_status0" {if $info['user_status'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if} ><span>关闭</span></label>
            <input id="user_status1" name="user_status" {if $info['user_status'] == 1}checked="checked"{/if} value="1" type="radio">
            <input id="user_status0" name="user_status" {if $info['user_status'] == 0}checked="checked"{/if} value="0" type="radio"></td>
          <td class="vatop tips"><span class="vatop rowform">可暂时将站点关闭，其他人无法访问，但不影响管理员访问后台</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="sex">{#sex#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="sex">
          		<option value="2" {if $info['sex'] == 2}selected{/if}>女</option>
          		<option value="1" {if $info['sex'] == 1}selected{/if}>男</option>
          	</select>
          </td>
        </tr>
        {if $info}
        <tr class="noborder">
          <td colspan="2" class="required">{#inviter_id#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" readonly value="{$info['inviter_id']|escape}" name="inviter_id" id="inviter_id" class="txt"></td>
        </tr>
        {/if}

        
        <tr>
			<td>
	    	{if $userExtend}
	    		<table>
	    			<thead>
	    				<tr>
	    					<th>用户扩展</th>
	    				</tr>
	    			</thead>
	    			<tbody>
						  {foreach from=$userExtend key=key item=valus}
        				<tr>
					        <tr class="noborder">
					          <td class="required">{#name#}</td>
					          <td class="required">{#user_from#}</td>
					          <td class="required">{#sex#}</td>
					          <td class="required">{#portrait#}</td>
					          <td class="required">{#region#}</td>
					        </tr>
					        <tr class="noborder">
					          <td class="required"><input type="text" readonly value="{$valus['name']|escape}" name="name" id="name" class="text"></td>
					          <td class="required"><input type="text" readonly value="{$valus['user_from']|escape}" name="user_from" id="user_from" class="text"></td>
					          <td class="required"><input type="text" readonly value="{if $valus['sex'] == 1}男{else if $valus['sex'] == 2}女{else}未设置{/if}" name="sex1" id="sex1" class="text"></td>
					          <td>
					          	<input type="hidden" name="portrait" value="{$valus['portrait']}" id="portrait"/>
					          	<div>
					          		<img id="view_img" src="{if $valus['portrait']}{resource_url($valus['portrait'])}{/if}"/>
					          	</div>
					          </td>
					          <td class="required"><input type="text" readonly value="{$valus['region']|escape}" name="region" id="region" class="text"></td>
					        </tr>
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
  <script type="text/javascript">
	submitUrl = [new RegExp("{$uri_string}")];
  </script>
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")],searchCompanyUrl = "{admin_site_url('company/getCompany')}";
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		
		bindAjaxSubmit("#infoform");
		
	    $( "#company_name" ).autocomplete({
			source: function( request, response ) {
				
				$.ajax( {
		            url: searchCompanyUrl,
		            dataType: "json",
		            data: {
		              term: request.term,
		              id:$("input[name=id]:checked").val(),
		            },
		            success: function( data ) {
		              response( data );
		            }
		          } 
				);
	        },
			minLength: 2,
	    });
	});
  </script>
{include file="common/main_footer.tpl"}