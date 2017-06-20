   {config_load file="person.conf"}
   {if $info['hid']}
   {form_open_multipart(site_url('house/edit?hid='|cat:$info['hid']),'id="user_form"')}
   {else}
   {form_open_multipart(site_url('house/add'),'id="user_form"')}
   {/if}
   	<fieldset>
   	   <legend>{if $info['hid']}编辑{else}添加{/if}存量建筑点</legend>
	   <input type="hidden" name="owner_id" value="{$info['owner_id']}" />
	   <input type="hidden" name="hid" value="{$info['hid']}"/>
	   <table class="fulltable noext formtable">
	   		<tbody>
		   		<tr>
		   			<td><lable class="required">X坐标(自动填入):</label></td>
		   			<td><input type="text" name="x" value="{$info['x']}"><div class="errtip" id="error_x"></div></td>
		   			<td><lable class="required">Y坐标(自动填入):</label></td>
		   			<td><input type="text" name="y" value="{$info['y']}"><div class="errtip" id="error_y"></div></td>
		   			<td><lable class="required">{#qlr_name#}:</label></td>
		   			<td><input type="text" name="owner_name" value="{$info['owner_name']|escape}" readonly><div class="errtip" id="error_owner_name"></div></td>
		   			<td><lable class="required">{#land_oa#}</label></td>
		   			<td>
		   				<select name="land_oa">
		   					<option value="">请选择</option>
		   					<option value="1" {if $info['land_oa'] == 1}selected{/if}>集体土地使用权</option>
		   					<option value="2" {if $info['land_oa'] == 2}selected{/if}>国有土地使用权</option>
		   				</select>
		   				<div class="errtip" id="error_land_oa"></div>
		   			</td>
		   		</tr>
		   		<tr>
		   			<td><lable>{#house_address#}{#village#}:</label></td>
		   			<td>
		   				<select name="village_id">
		   					<option value="">请选择</option>
		   					{foreach from=$villageList item=item}
		   					<option value="{$item['id']}" {if $item['id'] == $info['village_id']}selected{/if}>{$item['xzqmc']|escape}</option>
		   					{/foreach}
		   				</select>
		   				<div class="errtip" id="error_address"></div>
		   			</td>
		   			<td><lable>{#house_address#}{#address#}:</label></td>
		   			<td><input type="text" name="address" value="{$info['address']|escape}"><div class="errtip" id="error_address"></div></td>
		   			<td><lable>{#land_no#}:</label></td>
		   			<td><input type="text" name="land_no" value="{$info['land_no']|escape}" placeholder="如:慈集用(2001)字第080537号"><div class="errtip" id="error_land_no"></div></td>
		   			<td><lable>{#zddh#}:</label></td>
		   			<td><input type="text" name="zddh" value="{$info['zddh']|escape}" placeholder="如:82-8-46-234"><div class="errtip" id="error_zddh"></div></td>
		   			
		   		</tr>
		   		<tr>
		   			<td><lable>{#land_cate#}:</label></td>
		   			<td><input type="text" name="land_cate" value="{$info['land_cate']|escape}" placeholder="如:建设用地、住宅用地"><div class="errtip" id="error_land_cate"></div></td>
		   			<td><lable>{#jzw_ydmj#}:</label></td>
		   			<td><input type="text" name="jzw_ydmj" value="{$info['jzw_ydmj']|escape}"><div class="errtip" id="error_jzw_ydmj"></div></td>
		   			<td><lable>{#jzw_jzzdmj#}:</label></td>
		   			<td><input type="text" name="jzw_jzzdmj" value="{$info['jzw_jzzdmj']|escape}"><div class="errtip" id="error_jzw_jzzdmj"></div></td>
		   			<td><lable>{#jzw_jzmj#}:</label></td>
		   			<td><input type="text" name="jzw_jzmj" value="{$info['jzw_jzmj']|escape}"><div class="errtip" id="error_jzw_jzmj"></div></td>
		   		</tr>
		   		<tr>
		   			<td><lable>{#jzw_plies#}:</label></td>
		   			<td><input type="text" name="jzw_plies" value="{$info['jzw_plies']|escape}"><div class="errtip" id="error_jzw_plies"></div></td>
		   			<td><lable>{#jzw_jg#}:</label></td>
		   			<td><input type="text" name="jzw_jg" value="{$info['jzw_jg']|escape}" placeholder="如:砖混 , 木"><div class="errtip" id="error_jzw_jg"></div></td>
		   			<td><lable>{#jzw_unit#}:</label></td>
		   			<td><input type="text" name="jzw_unit" value="{$info['jzw_unit']|escape}"><div class="errtip" id="error_jzw_unit"></div></td>
		   			<td><lable>{#purpose#}:</label></td>
		   			<td><input type="text" name="purpose" value="{$info['purpose']|escape}" placeholder="如:村办公楼"><div class="errtip" id="error_purpose"></div></td>
		   		</tr>
		   		<tr>
		   			<td><lable>{#sp_new#}</label></td>
		   			<td><input type="text" name="sp_new" class="date" value="{if $info['sp_new']}{$info['sp_new']|date_format:"%Y-%m-%d"}{/if}"><div class="errtip" id="error_sp_new"></div></td>
		   			<td><lable>{#sp_ycyj#}:</label></td>
		   			<td><input type="text" name="sp_ycyj" class="date" value="{if $info['sp_ycyj']}{$info['sp_ycyj']|date_format:"%Y-%m-%d"}{/if}"><div class="errtip" id="error_sp_ycyj"></div></td>
		   			<td><lable>{#sp_ydmj#}:</label></td>
		   			<td><input type="text" name="sp_ydmj" value="{$info['sp_ydmj']|escape}"><div class="errtip" id="error_sp_ydmj"></div></td>
		   			<td><lable>{#sp_jzmj#}:</label></td>
		   			<td><input type="text" name="sp_jzmj" value="{$info['sp_jzmj']|escape}"><div class="errtip" id="error_sp_jzmj"></div></td>
		   			
		   		</tr>
		   		<tr>
		   			<td><lable>{#illegal#}:</label></td>
		   			<td>
		   				<select name="illegal">
		   					<option value="">请选择</option>
		   					<option value="0" {if $info['illegal'] === 0}selected{/if}>待定</option>
		   					<option value="1" {if $info['illegal'] == 1}selected{/if}>全部合法</option>
		   					<option value="2" {if $info['illegal'] == 2}selected{/if}>部分违法</option>
		   					<option value="3" {if $info['illegal'] == 3}selected{/if}>全部违法</option>
		   				</select>
		   				<div class="errtip" id="error_illegal"></div>
		   			</td>
		   			<td><lable>{#wf_wjsj#}:</label></td>
		   			<td><input type="text" name="wf_wjsj" class="date" value="{if $info['wf_wjsj']}{$info['wf_wjsj']|date_format:"%Y-%m-%d"}{/if}"><div class="errtip" id="error_wf_wjsj"></div></td>
		   			<td><lable>{#wf_wjmj#}</label></td>
		   			<td><input type="text" name="wf_wjmj" value="{$info['wf_wjmj']|escape}"><div class="errtip" id="error_wf_wjmj"></div></td>
		   			<td><lable>{#cate#}</label></td>
		   			<td>
		   				<select name="cate">
		   					<option value="">请选择</option>
		   					<option value="1" {if $info['cate'] == 1}selected{/if}>民宅类</option>
		   					<option value="2" {if $info['cate'] == 2}selected{/if}>公益类</option>
		   					<option value="3" {if $info['cate'] == 3}selected{/if}>经营类</option>
		   					<option value="4" {if $info['cate'] == 4}selected{/if}>农民公寓</option>
		   				</select>
		   				<div class="errtip" id="error_cate"></div>
		   			</td>
		   			
		   		</tr>
		   		<tr>
		   			<td><lable>{#deal_way#}</label></td>
		   			<td>
		   				<select name="deal_way">
		   					<option value="">请选择</option>
		   					<option value="1" {if $info['deal_way'] == 1}selected{/if}>暂缓</option>
		   					<option value="2" {if $info['deal_way'] == 2}selected{/if}>补办</option>
		   					<option value="3" {if $info['deal_way'] == 3}selected{/if}>没收</option>
		   					<option value="4" {if $info['deal_way'] == 4}selected{/if}>拆除</option>
		   				</select>
		   				<div class="errtip" id="error_deal_way"></div>
		   			</td>
		   			<td>添加照片</td>
		   			<td colspan="5">
		   				<div>
                            <a class="upload-button" href="javascript:void(0);"><span id="UploaderPlaceholder_1"></span></a>
                            <span class="Uploader" data-url="{site_url('house/addimg')}"  data-allowsize="1Mb" data-allowfile="*.jpg" ></span>
                            <span class="hightlight">请选择JPG格式照片文件，文件大小在1M以内</span>
                        </div>
		   			</td>
		   		</tr>
		   		<tr>
		   			<td>图片列表</td>
		   			<td colspan="7">
		   				<div class="field-box">
                            <div id="UploaderProgress_1"></div>
                            <div id="UploaderFeedBack_1"></div>
                        </div>
		   				<ul id="thumbnails" class="thumblists">
		       			{foreach from=$fileList item=item}
		       			<li id="{$item['id']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['id']}" /><div class="size-80x80"><span class="thumb"><i></i><a href="{resource_url($item['image_url_b'])}" class="fancybox" data-fancybox-group="gallery"><img src="{resource_url($item['image_url_m'])}" alt="" width="80px" height="80px"/></a></span></div><p><span><a href="javascript:del_file_upload('{$item['id']}');">删除</a></span></p></li>
		       			{/foreach}
		       			</ul>
		   			</td>
		   		</tr>
		   		<tr>
		   			<td><lable>备注:</label></td>
		   			<td colspan="7">
		   				<textarea class="w100pre" style="height:80px" name="remark">{$info['remark']|escape}</textarea>
		   			</td>
		   		</tr>
		   		<tr>
		   			<td>&nbsp;</td>
		   			<td colspan="7">
		   				<input type="submit" name="tijiao" class="master_btn" value="保存"/>
		   				<input type="reset" name="initbtn" class="master_btn grayed" value="重置"/>
		   			</td>
		   		</tr>
	   		</tbody>
	   </table>
   	  </fieldset>
   </form>
