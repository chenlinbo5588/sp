		        		{if $uploadEnable}
		        		<tr>
		        			<td>添加附件</td>
				   			<td>
				   				<div>
		                            <span id="UploaderPlaceholder_1"></span>
		                            <span class="Uploader" data-url="{site_url('budongchan/addfile')}"  data-allowsize="5Mb" data-allowfile="*.jpg;*.dwg;*.xls;*.xlsx;*.doc;*.docx;*.txt" ></span>
		                            <span class="hightlight">仅允许JPG、CAD、Word、Excel、TxT格式，大小在5M以内</span>
		                        </div>
				   			</td>
		        		</tr>
		        		{/if}
		        		<tr>
				   			<td class="vtop">附件列表</td>
				   			<td>
				   				<div class="field-box">
		                            <div id="UploaderProgress_1"></div>
		                            <div id="UploaderFeedBack_1"></div>
		                        </div>
				       			{foreach from=$fileList item=deptFileList key=key}
				       			<table id="ftb{$key}" class="bordered mg10">
		                        	<thead>
		                        		<tr>
		                        			<th colspan="5">上传单位:{$deptList[$key]['name']}</th>
		                        		</tr>
		                        		<tr>
		                        			<th>操作</th>
		                        			<th>文件名称</th>
		                        			<th>文件大小</th>
		                        			<th>上传人</th>
		                        			<th>上传时间</th>
		                        		</tr>
		                        	</thead>
		                        	<tbody>
				       				{foreach from=$deptFileList item=subitem}
						       			<tr id="file{$subitem['id']}">
						       				<td><input type="hidden" name="file_id[]" value="{$subitem['id']}"/>{if $profile['basic']['dept_id'] == $key}<a class="deleteFile" data-id="{$subitem['id']}" href="javascript:void(0);" data-url="{site_url('budongchan/deleteFile?bdc_id='|cat:$info['id'])}" data-title="{$subitem['orig_name']|escape}">删除</a>{/if}</td>
						       				<td>{if $info['id']}<a class="{attachmentExt($subitem['orig_name'])}" href="{site_url('budongchan/getfile?id='|cat:$info['id']|cat:'&fid='|cat:$subitem['id'])}">{$subitem['orig_name']|escape}</a>{else}<strong>{$subitem['orig_name']|escape}</strong>{/if}</td>
						       				<td>{byte_format($subitem['file_size'] * 1024)}</td>
						       				<td>{$subitem['username']|escape}</td>
						       				<td>{$subitem['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
						       			</tr>
				       				{/foreach}
				       				</tbody>
				       			</table>
				       			{/foreach}
				   			</td>
				   		</tr>