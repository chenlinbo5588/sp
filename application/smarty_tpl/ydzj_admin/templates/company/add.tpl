{include file="common/main_header_navs.tpl"}
  {config_load file="user.conf"}
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
          <td colspan="2" class="required"><label class="validation" for="mobile">{#mobile#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
          <td class="vatop tips"><label id="error_mobile"></label>{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#name#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips"><label id="error_name"></label>{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="company">{#company#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['company']|escape}" name="company" id="company" class="txt"></td>
          <td class="vatop tips"><label id="error_mobile"></label>{form_error('company')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="group">{#group#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['group']|escape}" name="group" id="group" class="txt"></td>
          <td class="vatop tips"><label id="error_mobile"></label>{form_error('group')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="user_type">{#user_type#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['user_type']|escape}" name="user_type" id="user_type" class="txt"></td>
          <td class="vatop tips"><label id="error_mobile"></label>{form_error('user_type')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="user_status">{#user_status#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['user_status']|escape}" name="user_status" id="user_status" class="txt"></td>
          <td class="vatop tips"><label id="error_mobile"></label>{form_error('user_status')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="sex">{#sex#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['sex'] == 1}男{else}女{/if}" name="sex" id="sex" class="txt"></td>
          <td class="vatop tips"><label id="error_mobile"></label>{form_error('sex')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="inviter_id">{#inviter_id#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['inviter_id']|escape}" name="inviter_id" id="inviter_id" class="txt"></td>
          <td class="vatop tips"><label id="error_mobile"></label>{form_error('inviter_id')}</td>
        </tr>
        

        
        
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
					          <td class="required"><input type="text" readonly value="{if $valus['sex'] == 1}男{else}女{/if}" name="sex" id="sex" class="text"></td>
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
  	var province_idcard = {$province_idcard},submitUrl = [new RegExp("{$uri_string}")];
  </script>
  <script type="text/javascript" src="{resource_url('js/service/yezhu.js',true)}"></script>
{include file="common/main_footer.tpl"}