{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <form class="formSearch" method="get" name="formSearch" id="formSearch" action="{site_url($uri_string)}">
	    <input type="hidden" name="page" value=""/>
	    <table class="tb-type1 noborder search">
	      <tbody>
	        <tr>
	          <td><select name="search_field_name" >
	              {foreach from=$search_map['search_field'] key=key item=item}
	              <option value="{$key}" {if $smarty.get['search_field_name'] == $key}selected{/if}>{$item}</option>
	              {/foreach}
	            </select>
	          </td>
	          <td><input type="text" value="{$smarty.get['search_field_value']}" name="search_field_value" class="txt"></td>
	          <td><label>{#apply_time#}</label></td>
	            <td><select name="apply_time">
	              {foreach from=$mtime key=key item=item}
	              <option  value="{$key}" {if $smarty.get['apply_time'] == $key}selected{/if}>{$key}</option>
	              {/foreach}
	            </select>
	            </td>
	            <td>认证状态</td>
	            <td>
	                <label><input type="checkbox" name="verify_result[]" {if in_array(0,$search_map['verify_result'])}checked{/if} value="0"/>未审核</label>
	                <label><input type="checkbox" name="verify_result[]" {if in_array(1,$search_map['verify_result'])}checked{/if} value="1"/>已通过</label>
	                <label><input type="checkbox" name="verify_result[]" {if in_array(-1,$search_map['verify_result'])}checked{/if} value="-1"/>未通过</label>
	            <td>
	              <input type="submit" class="msbtn" name="tijiao" value="查询"/>
	            </td>
	        </tr>
	      </tbody>
	    </table>
   
   
	   <table class="table tb-type2" id="prompt">
	    <tbody>
	      <tr class="space odd">
	        <th colspan="12"><div class="title">
	            <h5>操作提示</h5>
	            <span class="arrow"></span></div></th>
	      </tr>
	      <tr>
	        <td><ul>
	            <li>通过会员管理，你可以进行查看、编辑会员资料以及删除会员等操作</li>
	            <li>你可以根据条件搜索会员，然后选择相应的操作</li>
	          </ul></td>
	      </tr>
	    </tbody>
	  </table>
	   <table class="table tb-type2 nobdb">
	      <thead>
	        <tr class="thead">
	          {*<th><label><input type="checkbox" name="uid" class="checkall" />全选/取消</label></th>*}
	          <th>登陆账号</th>
	          <th>手机号码</th>
	          <th>邮箱</th>
	          <th>QQ</th>
	          <th>证件图片</th>
	          <th>提交时间</th>
	          <th>审核状态</th>
	          <th>备注</th>
	          <th>操作</th>
	        </tr>
	      </thead>
	      <tbody>
	      {foreach from=$list['data'] item=item key=key}
	        <tr class="hover member" id="row{$item['uid']}">
	          {*<td class="w24">{if $item['verify_result'] != 1}<input type="checkbox" name="id[]" group="uid" value="{$item['uid']}"/>{/if}</td>*}
	          <td>{$memberList[$item['uid']]['username']|escape}</td>
	          <td>{$memberList[$item['uid']]['mobile']|escape}</td>
	          <td><div class="im"><span class="email">{if $memberList[$item['uid']]['email'] != ''}<a href="mailto:{$memberList[$item['uid']]['email']}" class="yes" title="电子邮箱:{$memberList[$item['uid']]['email']|escape}">{$memberList[$item['uid']]['email']}</a>{/if}</span></div><span>{$memberList[$item['uid']]['email']|escape}</span></td>
	          <td><div class="im"><span class="qq">{if $memberList[$item['uid']]['qq'] != ''}<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$memberList[$item['uid']]['qq']}&site=qq&menu=yes" class="yes" alt="点击这里给我发消息  QQ: {$memberList[$item['uid']]['qq']|escape}" title="点击这里给我发消息 QQ: {$memberList[$item['uid']]['qq']|escape}">{$memberList[$item['uid']]['qq']|escape}</a>{/if}</span></div><span>{$memberList[$item['uid']]['qq']|escape}</span></td>
	          <td>{if substr($item['store_url'],0,4) == 'http'}<a href="{$item['store_url']}" target="_blank">{$item['store_url']|escape}</a>{else}{$item['store_url']}{/if}</td>
	          <td>
	           <a class="fancybox" href="{resource_url($item['source_pic'])}" title="交易流水"><img class="w108" src="{resource_url($item['trade_pic'])}" alt="" /></a>
	          </td>
	          <td>{time_tran($item['gmt_modify'])}</td>
	          <td>
	           {if $item['verify_result'] == 0}尚未审核
	           {elseif $item['verify_result'] == 1}已通过
	           {elseif $item['verify_result'] == -1}未通过
	           {/if}
	          </td>
	          <td>{$item['verify_remark']|escape}</td>
	          <td>
	              {if $item['verify_result'] != 1}
	              <a class="verify pass" data-title="{$memberList[$item['uid']]['username']|escape}审核通过" data-id="{$item['uid']}" href="javascript:void(0);" data-url="{admin_site_url('seller/verfiy')}?pass=1">审核通过</a> | 
	              <a class="verify unpass" data-title="{$memberList[$item['uid']]['username']|escape}审核不通过" data-id="{$item['uid']}" href="javascript:void(0);" data-url="{admin_site_url('seller/verfiy')}?pass=-1">不通过</a> | 
	              {/if}
	              <a href="{admin_site_url('notify/add?uid=')}{$item['uid']}">通知</a>
	          </td>
	        </tr>
	        {foreachelse}
	        <tr>
	           <td colspan="10">没有相应的记录</td>
	        </tr>
	      {/foreach}
	      </tbody>
	    </table>
	    {*
	    <div class="pd5">
	        <label><input type="checkbox" class="checkall" name="uid" /><strong>全选/取消&nbsp;</strong></label>
	        <input type="button" class="btn updateBtn" data-checkbox="id[]" data-title="审核通过" data-url="{admin_site_url('seller/verfiy')}?pass=0" name="verfiy_pass" value="审核通过"/>
	    </div>
	    *}
	    {include file="common/pagination.tpl"}
    </form>
    <div id="reasonDlg" title="卖家认证未通过" style="display:none;">
        <div class="loading_bg" style="display:none;">发送中...</div>
        <form id="verifyForm" action="{admin_site_url('seller/verify')}" method="post">
            <input type="hidden" name="pass" value=""/>
            <input type="hidden" name="id" value=""/>
            <table class="fulltable noborder">
                <tr>
                    <td>通知方式:
                        {foreach from=$sendWays key=key item=item}
                        <label><input type="checkbox" name="send_ways[]" checked value="{$item}"/>{$item}</label>
                        {/foreach}
                    </td>
                </tr>
                <tr><td><textarea name="remark" class="at_txt" style="height:150px" placeholder="请输入原因，最多30个字"></textarea></td></tr>
                <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="确定"/></td></tr>
            </table>
        </form>
    </div>
    {include file="common/fancybox.tpl"}
    {include file="common/jquery_validation.tpl"}
    <script type="text/javascript" src="{resource_url('js/admin/verify.js')}"></script>
{include file="common/main_footer.tpl"}