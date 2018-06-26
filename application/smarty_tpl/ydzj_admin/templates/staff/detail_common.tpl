		<tr class="noborder">
          <td colspan="2" class="required">{#people_photo#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<a class="fancybox" data-fancybox-group="galleryAvatar" href="{resource_url($workerInfo['avatar_b'])}">
          	{if $workerInfo['avatar_s']}
          	<img src="{resource_url($workerInfo['avatar_s'])}"/>
          	{else if $workerInfo['avatar_m']}
          	<img src="{resource_url($workerInfo['avatar_m'])}"/>
          	{elseif $workerInfo['avatar_b']}
          	<img src="{resource_url($workerInfo['avatar_b'])}"/>
          	{/if}
          	</a>
          <td class="vatop tips"></td>
        </tr>
       	<tr class="noborder">
          <td colspan="2" class="required">{#other_photo#}</td>
        </tr>
        <tr class="noborder">
       		<td class="vatop rowform" colspan="2">
       			{if $workerFileList}
       			<ul class="thumblists">
       			{foreach from=$workerFileList item=item}
       			<li class="picture"><div class="size-64x64"><span class="thumb"><i></i><a class="fancybox" href="{resource_url($item['image_b'])}" data-fancybox-group="galleryWorker"><img src="{resource_url($item['image_m'])}" alt="" width="64px" height="64px"/></a></span></div></li>
       			{/foreach}
       			</ul>
       			{else}
       			暂无
       			{/if}
       		</td>
       	</tr>
       	{if '保姆' == $moduleTitle || '护工' == $moduleTitle}
       	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{$moduleTitle}{#type#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<ul class="">
	          {foreach from=$subTypeList item=item}
	          <label><input type="radio" name="sub_type" value="{$item['id']}" {if $inPost}{set_radio('sub_type',$item['id'])}{else}{if $info['sub_type'] == $item['id']}checked="checked"{/if}{/if}/>{$item['show_name']}</label>
	          {/foreach}
	        </ul>
          </td>
          <td class="vatop tips">{form_error('sub_type')}</td>
        </tr>
       	{/if}
       	
       	{if '护工' == $moduleTitle}
       	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{$moduleTitle}{#grade#}:</label></td>
        </tr>
       	<tr class="noborder">
          <td class="vatop rowform">
          	{foreach from=$gradeList item=item}
          	<label><input type="radio" name="grade" value="{$item['id']}" {if $inPost}{set_radio('grade',$item['id'])}{else}{if $info['grade'] == $item['id']}checked="checked"{/if}{/if}/>{$item['show_name']}</label>
          	{/foreach}
          </td>
          <td class="vatop tips">{form_error('grade')}</td>
        </tr>
       	{/if}
        {include file="worker/basic_info.tpl"}
       	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#work_month#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['work_month']|escape}" name="work_month" id="work_month" class="txt"></td>
          <td class="vatop tips">{form_error('work_month')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#service_cnt#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['service_cnt']|escape}" name="service_cnt" id="service_cnt" class="txt"><span>户</span></td>
          <td class="vatop tips">{form_error('service_cnt')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#region#}:</label>{form_error('region')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan="2">
          	<ul class="">
	          {foreach from=$regionList item=item}
	          <label><input type="radio" name="region" value="{$item['id']}" {if $inPost}{set_radio('region',$item['id'])}{else}{if $info['region'] == $item['id']}checked="checked"{/if}{/if}/>{$item['show_name']}</label>
	          {/foreach}
	        </ul>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="jiguan">{#salary#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="salary" id="salary">
	          <option value="">请选择...</option>
	          {foreach from=$salaryList item=item}
	          <option {if $info['salary'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('salary')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#salary_detail#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['salary_detail']|escape}" name="salary_detail" id="salary_detail" class="txt"></td>
          <td class="vatop tips">{form_error('salary_detail')} <span>如: <strong id="fillText" title="点击填入" data-id="#salary_detail">{if $moduleTitle == '月嫂'}12000元/26天{elseif $moduleTitle == '保姆'}5000元/月{elseif $moduleTitle == '护工'}230元/每天{/if}</strong></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{$moduleTitle}{#serv_ablity#}:</label>{form_error('serv_ablity[]')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop" colspan="2">
          	<ul class="ulListStyle1 clearfix">
          	{foreach from=$ablityList key=keyName item=itemName}
          		{if !empty($itemName['children'])}
          			<li class="title"><h4 class="clearfix">{$keyName}</h4></li>
          		{/if}
          		{if empty($itemName['children'])}
          			<li><label><input type="checkbox" name="serv_ablity[]" {if $inPost}{set_checkbox('serv_ablity[]',$itemName['id'])}{else}{if in_array($itemName['id'],$info['serv_ablity'])}checked="checked"{/if}{/if} value="{$itemName['id']}"/>{$itemName['show_name']|escape}</label></li>
          		{else}
          			{foreach from=$itemName['children'] key=subKey item=subItem}
          			<li><label><input type="checkbox" name="serv_ablity[]" value="{$subItem['id']}" {if $inPost}{set_checkbox('serv_ablity[]',$subItem['id'])}{else}{if in_array($subItem['id'],$info['serv_ablity'])}checked="checked"{/if}{/if}/>{$subItem['show_name']|escape}</label></li>
          			{/foreach}
          		{/if}
          		
          	{/foreach}
          	</ul>
          </td>
        </tr>
        {if '月嫂' == $moduleTitle}
        <tr>
          <td colspan="2" class="required"><label class="validation">{#sbt_exp#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="sbt_exp1" {if $info['sbt_exp'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="sbt_exp0" {if $info['sbt_exp'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="sbt_exp1" name="sbt_exp" {if $info['sbt_exp'] == 1}checked{/if} value="1" type="radio">
            <input id="sbt_exp0" name="sbt_exp" {if $info['sbt_exp'] == 0}checked{/if} value="0" type="radio">
          </td>
          <td class="vatop tips">{form_error('sbt_exp')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">{#zcbaby_exp#}: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="zcbaby_exp1" {if $info['zcbaby_exp'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="zcbaby_exp0" {if $info['zcbaby_exp'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="zcbaby_exp1" name="zcbaby_exp" {if $info['zcbaby_exp'] == 1}checked{/if} value="1" type="radio">
            <input id="zcbaby_exp0" name="zcbaby_exp" {if $info['zcbaby_exp'] == 0}checked{/if} value="0" type="radio">
          </td>
          <td class="vatop tips">{form_error('zcbaby_exp')}</td>
        </tr>
        {/if}