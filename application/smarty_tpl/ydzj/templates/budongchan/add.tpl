{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
    {config_load file="budongchan.conf"}
    {include file="common/swfupload.tpl"}
    {if $info['id']}
    {form_open(site_url('budongchan/edit?id='|cat:$info['id']))}
	<input type="hidden" name="id" value="{$info['id']}"/>
	{else}
	{form_open(site_url($uri_string))}
	{/if}
		<fieldset>
   	   		<legend>{if $info['id']}编辑{else}添加{/if}-不动产业务登记</legend>
	        <table class="fulltable formtable">
	        	<tbody>
	        		{if $info['lsno']}
	        		<tr>
	        			<td class="w120">流水号</td>
	        			<td>{$info['lsno']}</td>
	        		</tr>
	        		{/if}
	        		<tr>
	        			<td class="w120"><label class="required">{#bdc_name#}</label></td>
	        			<td>
	        				<input type="text" class="w50pre" name="name" value="{$info['name']|escape}"/>
	        				<span>{form_error('name')}</span>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td><label class="required">{#cm_address#}</label></td>
	        			<td>
	        				<input type="text" class="w50pre" name="address" value="{$info['address']|escape}" placeholder="如：古塘街道新城大道北路1122号"/>
	        				<span>{form_error('address')}</span>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td><label class="required">{#cm_contact_name#}</label></td>
	        			<td>
	        				<input type="text" name="contactor" value="{$info['contactor']|escape}" placeholder="如：张三"/>
	        				<span class="tip">如：张三</span>&nbsp;{form_error('contactor')}
	        			</td>
	        		</tr>
	        		<tr>
	        			<td><label class="required">{#cm_mobile#}</label></td>
	        			<td>
	        				<input type="text" name="mobile" value="{$info['mobile']|escape}"/>
	        				{form_error('mobile')}
	        			</td>
	        		</tr>
	        		<tr>
	        			<td><label class="required">{#cm_id_type#}</label></td>
	        			<td>
	        				{foreach from=$id_type item=item key=key}
	        				<label><input type="radio" value="{$key}" name="id_type" {if $info['id_type'] == $item || $info['id_type'] == $key}checked{/if}/>{$item}</label>
	        				{/foreach}	
	        			</td>
	        		</tr>
	        		<tr>
	        			<td><label class="required">{#cm_id_no#}</label></td>
	        			<td>
	        				<input type="text" class="w50pre" name="id_no" value="{$info['id_no']|escape}"/>
	        				<span>{form_error('id_no')}</span>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td class="vtop"><label>{#cm_remark#}</label></td>
	        			<td>
	        				<textarea class="w50pre" style="height:180px;" name="remark">{$info['remark']|escape}</textarea>
	        				<span>{form_error('remark')}</span>
	        			</td>
	        		</tr>
	        		<tr>
	        			<td>添加附件</td>
			   			<td>
			   				<div>
	                            <span id="UploaderPlaceholder_1"></span>
	                            <span class="Uploader" data-url="{site_url('budongchan/addfile')}"  data-allowsize="1Mb" data-allowfile="*.jpg" ></span>
	                            <span class="hightlight">请选择JPG格式扫描文件，文件大小在1M以内</span>
	                        </div>
			   			</td>
	        		</tr>
	        		<tr>
			   			<td class="vtop">附件列表</td>
			   			<td>
			   				<div class="field-box">
	                            <div id="UploaderProgress_1"></div>
	                            <div id="UploaderFeedBack_1"></div>
	                        </div>
			   				<ul id="thumbnails" class="filelist" >
			       			{foreach from=$fileList item=item}
			       			<li id="{$item['id']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['id']}" /><a href="javascript:del_file_upload({$item['id']});">删除</a>&nbsp;<strong>{$item['orig_name']}</strong></li>
			       			{/foreach}
			       			</ul>
			   			</td>
			   		</tr>
	        	</tbody>
	        	<tfoot>
	        		<tr>
	        			<td></td>
	        			<td>
	        				<input type="submit" name="tijiao" class="master_btn" value="保存"/>
	        				<a href="{site_url('budongchan/index')}" class="master_btn grayed">返回</a>
	        			</td>
	        		</tr>
	        	</tfoot>
	        </table>
	      </fieldset>
    </form>
    <script>
    	function add_uploadedfile(file_data)
	  	{
	  		var newImg = '<li id="file' + file_data.id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.id + '"/><a href="javascript:del_file_upload(' + file_data.id + ');">删除</a>&nbsp;<strong>' + file_data.orig_name + '</strong></li>';
		    $('#thumbnails').append(newImg);
	   	}
	   	
	   	function del_file_upload(file_id){
	   		$('#file' + file_id).remove();
	   	}
	   
    	$(function(){
	    	$(".Uploader").each(function(index){
	            var handler = {
	                success:function(file,serverData){
	                    try {
	                        var progress = new FileProgress(file, this.customSettings.progressTarget);
	                        progress.setComplete();
	                        progress.setStatus("Complete.");
	                        progress.toggleCancel(false);
	
	                        if(typeof(this.customSettings.callback) != "undefined"){
	                            this.customSettings.callback(file,serverData);
	                        }
	                        
	                        var response = $.parseJSON(serverData);
	                        
	                        if(response.data.error == 0){
	                        	add_uploadedfile(response.data);
	                        }
	
	                    } catch (ex) {
	                        this.debug(ex);
	                    }
	                }
	            };
	
	            createSwfUpload(index + 1,$(this).attr("data-url"),{ formhash: formhash, lsno: "{$info['lsno']}" },$(this).attr("data-allowsize"),$(this).attr("data-allowfile"),handler);
	        });
            
	    	{if $message == '保存成功'}
    		showToast('success','{$message},3秒后跳转',{ 
    			hideAfter:2000,
    			afterHidden: function () {
			        location.href="{site_url('budongchan/edit?id='|cat:$info['id'])}";
			    }
    		});
	    	{/if}
    	})
    </script>
{include file="common/my_footer.tpl"}

