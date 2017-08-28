{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
    {if $uploadEnable}
    {include file="common/swfupload.tpl"}
    {/if}
    <div id="bdcWrap">
    	{$stepHTML}
	    {if $info['id']}
	    {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="addForm"')}
		<input type="hidden" name="id" value="{$info['id']}"/>
		{else}
		{form_open(site_url($uri_string),'id="addForm"')}
		{/if}
			<fieldset>
	   	   		<legend>{if $info['id']}编辑{else}添加{/if}-不动产业务</legend>
		        <table class="fulltable formtable">
		        	<tbody>
		        		{if $info['lsno']}
		        		<tr>
		        			<td class="w120">流水号</td>
		        			<td><span class="lsno">{$info['lsno']}</span></td>
		        		</tr>
		        		{/if}
		        		<tr>
		        			<td class="w120"><label class="required">{#bdc_name#}</label></td>
		        			<td>
		        				<input type="text" id="elem_name" class="w50pre" name="name" value="{$info['name']|escape}"/>
		        				<span class="errtip" id="error_name">{form_error('name')}</span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_address#}</label></td>
		        			<td>
		        				<input type="text" id="elem_address" class="w50pre" name="address" value="{$info['address']|escape}" placeholder="如：古塘街道新城大道北路1122号"/>
		        				<span class="errtip" id="error_address">{form_error('address')}</span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_contact_name#}</label></td>
		        			<td>
		        				<input type="text" id="elem_contactor" name="contactor" value="{$info['contactor']|escape}" placeholder="如：张三"/>
		        				<span class="errtip" id="error_contactor" class="tip">如：张三</span>&nbsp;{form_error('contactor')}
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_mobile#}</label></td>
		        			<td>
		        				<input type="text" id="elem_mobile" name="mobile" value="{$info['mobile']|escape}"/>
		        				<span class="errtip" id="error_mobile">{form_error('mobile')}</span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_id_type#}</label></td>
		        			<td>
		        				{foreach from=$id_type item=item key=key}
		        				<label><input type="radio" value="{$key}" name="id_type" {if $info['id_type'] == $item || $info['id_type'] == $key}checked{/if}/>{$item}</label>
		        				{/foreach}
		        				<span class="errtip" id="error_id_type"></span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_id_no#}</label></td>
		        			<td>
		        				<input type="text" id="elem_id_no" class="w50pre" name="id_no" value="{$info['id_no']|escape}"/>
		        				<span class="errtip" id="error_id_no">{form_error('id_no')}</span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td class="vtop"><label>{#cm_remark#}</label></td>
		        			<td>
		        				<textarea class="w50pre" id="elem_bz" style="height:180px;" name="bz">{$info['bz']|escape}</textarea>
		        				<span class="errtip" id="error_bz">{form_error('bz')}</span>
		        			</td>
		        		</tr>
		        		{include file="./jinban_info.tpl"}
		        		{include file="./file_list.tpl"}
				   		{if $orgList}
				   		{include file="./sender_list.tpl"}
				   		{/if}
		        	</tbody>
		        </table>
		      </fieldset>
		      <div class="fixbar">
				{if $showSaveButton}
				<input type="submit" name="tijiao" class="master_btn" value="保存"/>
				{/if}
				
				{if $showShouliButton}
				<input type="submit" name="tijiao" class="master_btn" value="受理"/>
				{/if}
				
				{if $showRevertButton}
				<input type="submit" name="tijiao" class="master_btn" value="撤销提交"/>
				{/if}
				
				{if $orgList}
					<input type="submit" name="tijiao" class="master_btn" value="发送"/>
				{/if}
				<a href="{site_url('budongchan/index')}" class="master_btn grayed">返回</a>
			</div>
	    </form>
    </div>
    {if $uploadEnable}
    {include file="./file_event.tpl"}
    {/if}
    <script>
    	var urlsConfig = [ new RegExp('{site_url('budongchan/deleteFile')}'), new RegExp('{site_url('budongchan/add')}'), new RegExp('{site_url('budongchan/edit')}') ];
    </script>
    <script type="text/javascript" src="{resource_url('js/my/budongchan.js',true)}"></script>
{include file="common/my_footer.tpl"}

