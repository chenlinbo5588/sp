   {config_load file="person.conf"}
   	<fieldset>
   	   <legend>存量建筑点-{$inf['owner_name']}</legend>
	   <input type="hidden" name="owner_id" value="{$info['owner_id']}" />
	   <input type="hidden" name="hid" value="{$info['hid']}"/>
	    <div class="hightlight">注:面积单位统一平方米 M<sup>2</sup></div>
	   	<div id="tabs">
		  <ul>
		    <li><a href="#tabs-basic">基本信息</a></li>
		    <li><a href="#tabs-house">房屋信息</a></li>
		    <li><a href="#tabs-housedetail">房屋明细</a></li>
		  </ul>
		  <div id="tabs-basic">
		  	<table class="fulltable formtable">
		   		<tbody>
			   		<tr>
			   			<td class="w150"><label class="required">X坐标(自动填入):</label></td>
			   			<td><input type="text" name="x" value="{$info['x']}"><div class="errtip" id="error_x"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label class="required">Y坐标(自动填入):</label></td>
			   			<td><input type="text" name="y" value="{$info['y']}"><div class="errtip" id="error_y"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label class="required">{#qlr_name#}:</label></td>
			   			<td><input type="text" name="owner_name" value="{$info['owner_name']|escape}" readonly><div class="errtip" id="error_owner_name"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label class="required">{#land_oa#}</label></td>
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
			   			<td><label>{#house_address#}{#village#}:</label></td>
			   			<td>
			   				<select name="village_id">
			   					<option value="">请选择</option>
			   					{foreach from=$villageList item=item}
			   					<option value="{$item['id']}" {if $item['id'] == $info['village_id']}selected{/if}>{$item['xzqmc']|escape}</option>
			   					{/foreach}
			   				</select>
			   				<div class="errtip" id="error_address"></div>
			   			</td>
			   		</tr>
			   		<tr>
			   			<td><label>{#house_address#}{#address#}:</label></td>
			   			<td><input type="text" name="address" value="{$info['address']|escape}"><div class="errtip" id="error_address"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label>{#zddh#}:</label></td>
			   			<td><input type="text" name="zddh" value="{$info['zddh']|escape}" placeholder="如:82-8-46-234"><div class="errtip" id="error_zddh"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label>{#land_no#}:</label></td>
			   			<td><input type="text" name="land_no" value="{$info['land_no']|escape}" placeholder="如:慈集用(2001)字第080537号"><div class="errtip" id="error_land_no"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label>{#pw_no#}:</label></td>
			   			<td><input type="text" name="pw_no" value="{$info['pw_no']|escape}" placeholder="如:慈土批字(88)第75号"><div class="errtip" id="error_pw_no"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label>{#land_cate#}:</label></td>
			   			<td><input type="text" name="land_cate" value="{$info['land_cate']|escape}" placeholder="如:建设用地、住宅用地"><div class="errtip" id="error_land_cate"></div></td>
			   		</tr>
		  		</tbody>
		  	</table>
		  </div>
		  <div id="tabs-house">
		  	<table class="fulltable formtable">
		   		<tbody>
		  			<tr>
			   			<td class="w120"><label>{#sp_new#}</label></td>
			   			<td><input type="text" name="sp_new" class="date" value="{if $info['sp_new']}{$info['sp_new']|date_format:"%Y-%m-%d"}{/if}"><div class="errtip" id="error_sp_new"></div></td>
			   			<td><label>{#sp_ycyj#}:</label></td>
			   			<td><input type="text" name="sp_ycyj" class="date" value="{if $info['sp_ycyj']}{$info['sp_ycyj']|date_format:"%Y-%m-%d"}{/if}"><div class="errtip" id="error_sp_ycyj"></div></td>
			   			<td colspan="4"></td>
			   		</tr>
			   		<tr>
			   			<td><label>{#sp_jz#}:</label></td>
			   			<td><input type="text" name="sp_jz" value="{$info['sp_jz']|escape}" placeholder="如:2间*2楼"><div class="errtip" id="error_sp_jz"></div></td>
			   			<td><label>{#sp_ydmj#}:</label></td>
			   			<td><input type="text" name="sp_ydmj" value="{$info['sp_ydmj']|escape}"><div class="errtip" id="error_sp_ydmj"></div></td>
			   			<td><label>{#sp_jzzdmj#}:</label></td>
			   			<td><input type="text" name="sp_jzzdmj" value="{$info['sp_jzzdmj']|escape}"><div class="errtip" id="error_sp_jzzdmj"></div></td>
			   			<td><label>{#sp_jzmj#}:</label></td>
			   			<td><input type="text" name="sp_jzmj" value="{$info['sp_jzmj']|escape}"><div class="errtip" id="error_sp_jzmj"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label>{#jzw_ydmj#}:</label></td>
			   			<td><input type="text" name="jzw_ydmj" value="{$info['jzw_ydmj']|escape}"><div class="errtip" id="error_jzw_ydmj"></div></td>
			   			<td><label>{#jzw_jzzdmj#}:</label></td>
			   			<td><input type="text" name="jzw_jzzdmj" value="{$info['jzw_jzzdmj']|escape}"><div class="errtip" id="error_jzw_jzzdmj"></div></td>
			   			<td><label>{#jzw_jzmj#}:</label></td>
			   			<td><input type="text" name="jzw_jzmj" value="{$info['jzw_jzmj']|escape}"><div class="errtip" id="error_jzw_jzmj"></div></td>
			   			<td><label>{#jzw_plies#}:</label></td>
			   			<td><input type="text" name="jzw_plies" value="{$info['jzw_plies']|escape}" placeholder="如:2间*2楼"><div class="errtip" id="error_jzw_plies"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label>{#jzw_jg#}:</label></td>
			   			<td><input type="text" name="jzw_jg" value="{$info['jzw_jg']|escape}" placeholder="如:砖混 , 木"><div class="errtip" id="error_jzw_jg"></div></td>
			   			<td><label>{#jzw_unit#}:</label></td>
			   			<td><input type="text" name="jzw_unit" value="{$info['jzw_unit']|escape}"><div class="errtip" id="error_jzw_unit"></div></td>
			   			<td><label>{#purpose#}:</label></td>
			   			<td><input type="text" name="purpose" value="{$info['purpose']|escape}" placeholder="如:村办公楼"><div class="errtip" id="error_purpose"></div></td>
				   		<td><label class="required">{#illegal#}:</label></td>
			   			<td>
			   				<select name="illegal">
			   					<option value="">请选择</option>
			   					<option value="0" {if isset($info['illegal']) &&  $info['illegal'] == 0}selected{/if}>待定</option>
			   					<option value="1" {if $info['illegal'] == 1}selected{/if}>全部合法</option>
			   					<option value="2" {if $info['illegal'] == 2}selected{/if}>部分违法</option>
			   					<option value="3" {if $info['illegal'] == 3}selected{/if}>全部违法</option>
			   				</select>
			   				<div class="errtip" id="error_illegal"></div>
			   			</td>
			   		</tr>
			   		<tr>
			   			<td><label>{#wf_wjsj#}:</label></td>
			   			<td><input type="text" name="wf_wjsj" class="date" value="{if $info['wf_wjsj']}{$info['wf_wjsj']|date_format:"%Y-%m-%d"}{/if}"><div class="errtip" id="error_wf_wjsj"></div></td>
			   			<td><label>{#wf_ydmj#}</label></td>
			   			<td><input type="text" name="wf_ydmj" value="{$info['wf_ydmj']|escape}"><div class="errtip" id="error_wf_ydmj"></div></td>
			   			<td><label>{#wf_jzzdmj#}</label></td>
			   			<td><input type="text" name="wf_jzzdmj" value="{$info['wf_jzzdmj']|escape}"><div class="errtip" id="error_wf_jzzdmj"></div></td>
			   			<td><label>{#wf_jzmj#}</label></td>
			   			<td><input type="text" name="wf_jzmj" value="{$info['wf_jzmj']|escape}"><div class="errtip" id="error_wf_jzmj"></div></td>
			   		</tr>
			   		<tr>
			   			<td><label>{#cate#}</label></td>
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
			   			<td><label>{#deal_way#}</label></td>
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
			   			<td colspan="3">
			   				
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
			       			<li id="{$item['id']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['id']}" /><div class="size-80x80"><span class="thumb"><i></i><a href="{resource_url($item['image_url_b'])}" class="fancybox" data-fancybox-group="gallery"><img src="{resource_url($item['image_url_m'])}" alt="" width="80px" height="80px"/></a></span></div></li>
			       			{/foreach}
			       			</ul>
			   			</td>
			   		</tr>
			   		<tr>
			   			<td><label>备注:</label></td>
			   			<td  colspan="7">
			   				<textarea class="w100pre" style="height:80px" name="remark">{$info['remark']|escape}</textarea>
			   			</td>
			   		</tr>
		  		</table>
		  	</table>
		  </div>
		  <div id="tabs-housedetail">
		  	<table class="fulltable noext formtable">
		  		<thead>
		  			<tr>
		  				<th>建筑占地面积(㎡)</th>
		  				<th>房屋结构</th>
		  				<th>建筑信息（间*层）</th>
		  				<th>建筑面积(㎡)</th>
		  				<th>备注</th>
		  			</tr>
		  		</thead>
		   		<tbody>
		   			{foreach from=$info['houseDetail'] item=item key=key}
			   		<tr>
			   			<td>{$item['jzw_jzzdmj']|escape}</td>
			   			<td>{$item['jzw_jg']|escape}</td>
			   			<td>{$item['jzw_plies']|escape}</td>
			   			<td>{$item['jzw_jzmj']|escape}</td>
			   			<td>{$item['remark']|escape}</td>
			   		</tr>
			   		{/foreach}
			   	</tbody>
			 </table>
		  </div>
		</div>
   		
   	  </fieldset>
   <script>
   	 $(function(){
   	 	$( "#tabs" ).tabs({
	      collapsible: true
	    });
   	 });
   </script>
