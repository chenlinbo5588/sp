{include file="common/my_header.tpl"}
    {config_load file="person.conf"}
    
    {if $info['id']}
	<form action="{site_url('person/edit?id='|cat:$info['id'])}" method="post" id="pubForm">
	<input type="hidden" name="id" value="{$info['id']}"/>
	{else}
	<form action="{site_url($uri_string)}" method="post" id="pubForm">
	{/if}
		<fieldset>
   	   		<legend>基本资料</legend>
	        <table class="fulltable formtable">
	        	<tbody>
	        		<tr>
	        			<td class="w120"><label class="required">{#village#}</label></td>
	        			<td>{$profile['basic']['village']|escape}</td>
	        		</tr>
	        		<tr>
	        			<td><label class="required">{#qlr_name#}</label></td>
	        			<td>
	        				<input type="text" class="w50pre" name="qlr_name" value="{$info['qlr_name']|escape}"/>
	        				<span>{form_error('qlr_name')}</span>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td><label class="required">{#id_type#}</label></td>
	        			<td>
	        				<label><input type="radio" value="居民身份证" name="id_type" {if $info['id_type'] == '居民身份证' || $info['id_type'] == 1}checked{/if}/>居民身份证</label>
	        				<label><input type="radio" value="工商营业执照" name="id_type" {if $info['id_type'] == '工商营业执照' || $info['id_type'] == 2 }checked{/if}/>工商营业执照</label>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td><label class="required">{#id_no#}</label></td>
	        			<td>
	        				<input type="text" class="w50pre" name="id_no" value="{$info['id_no']|escape}"/>
	        				<span>{form_error('id_no')}</span>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td>{#address#}</td>
	        			<td>
	        				<input type="text" class="w50pre" name="address" value="{$info['address']|escape}"/>
	        				<span>{form_error('address')}</span>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td>{#contact_name#}</td>
	        			<td>
	        				<input type="text" name="name" value="{$info['name']|escape}"/>
	        				<span class="tip">当权利人为法人单位时 填写联系人信息</span>{form_error('name')}
	        			</td>
	        		</tr>
	        		<tr>
	        			<td>{#sex#}</td>
	        			<td>
	        				<label><input type="radio" value="1" name="sex" {if $info['sex'] == 1}checked{/if}/>男</label>
	        				<label><input type="radio" value="2" name="sex" {if $info['sex'] == 2}checked{/if}/>女</label>
	        				
	        				<span>{form_error('sex')}</span>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td>{#mobile#}</td>
	        			<td>
	        				<input type="text" name="mobile" value="{$info['mobile']|escape}"/>
	        				{form_error('mobile')}
	        			</td>
	        		</tr>
	        		<tr>
	        			<td>{#family_num#}</td>
	        			<td>
	        				<input type="text"  name="family_num" value="{$info['family_num']|escape}"/>
	        				<span>{form_error('family_num')}</span>
	        			</td>
	        		</tr>
	        		
	        	</tbody>
	        	<tfoot>
	        		<tr>
	        			<td></td>
	        			<td>
	        				<input type="submit" name="tijiao" class="master_btn" value="保存"/>
	        				<a href="{site_url('person/index')}" class="master_btn grayed">返回</a>
	        			</td>
	        		</tr>
	        	</tfoot>
	        </table>
	      </fieldset>
    </form>
   
{include file="common/my_footer.tpl"}

