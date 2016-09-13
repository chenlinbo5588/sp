{include file="my/my_header.tpl"}
	<form action="{site_url('goods/index')}" method="post" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="rel simpleclearfix">
	        <div class="codewrap fl">
	        	<textarea name="gc" placeholder="输入货号，每行一个货号, 也可以按逗号分隔，一次最多可同时50个">{$smarty.post.gc}</textarea>
	        </div>
	        <div class="searchtip fl">
                <div class="tip">单行模式: 229284000,884926-006,148777000</div>
                <div class="tip">多行莫斯: <br/>229284000<br/>884926-006<br/>148777000<br/>....</div>
            </div>
            <div class="otherwrap ">
	            <input type="text" name="s1" style="width:15%" value="{$smarty.post.s1}" placeholder="尺寸下限(CN) 空标志无下限"/>
	            <input type="text" name="s2" style="width:15%" value="{$smarty.post.s2}" placeholder="尺寸上限(CN) 空标志无上限"/>
	            <input type="text" name="cnum" style="width:15%" value="{if $smarty.post.cnum}{$smarty.post.cnum}{else}1{/if}" placeholder="请填入剩余数量大于等于几，默认1"/>
	            <input class="master_btn fr" style="width:45%;" type="submit" name="search" value="查询"/>
	        </div>
        </div>
        {*
        <div class="searcharea">
            <label><span>货品名称</span><input type="text" name="gn" value="{$smarty.post.gn}" placeholder="货品名称"></label>
            <label><span>货号</span><input type="text" name="gc" value="{$smarty.post.gc}" placeholder="9位货号"></label>
            <label><span>尺寸范围下限</span>
            	<input type="text" name="s1" style="width:60px" value="{$smarty.post.s1}" placeholder="尺寸下限(CN) 空标志无下限">
            </label>
            <label><span>尺码最大</span>
            	<input type="text" name="s2" style="width:60px" value="{$smarty.post.s2}" placeholder="尺寸上限(CN) 空标志无上限">
            </label>
            <label><span>剩余数量</span><input type="text" name="cnum" value="{$smarty.post.cnum}" placeholder="剩余数量"></label>
        </div>
        *}
        <div class="warning">用户在线沟通成交后，请<strong>求货信息发布方</strong>点击成交链接完成交易.为了平台的信息的准确性和实时性。请在交易完成后及时点击完成交易。</div>
        <div>{include file="common/pagination.tpl"}</div>
        <table class="fulltable">
            <thead>
                <tr>
                    <th>求货序号</th>
                    <th>货品名称</th>
                    <th>货号</th>
                    <th>尺码(CN)</th>
                    <th>求购数量(剩余)</th>
                    <th>发布人</th>
                    <th>发布时间</th>
                    <th>最后修改时间</th>
                    <th>操作</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr>
		           <td>{$item['goods_id']}</td>
		           <td>{$item['goods_name']|escape}</td>
		           <td>{$item['goods_code']|escape}</td>
		           <td>{$item['goods_size']}</td>
		           <td>{$item['quantity']}&nbsp;({$item['cnum']})</td>
		           <td>
		              {if $userList[$item['uid']]['qq']}
		              <a target="_blank" class="qqchat" href="http://wpa.qq.com/msgrd?v=3&uin={$userList[$item['uid']]['qq']}&site=qq&menu=yes" alt="点击这里给我发消息" title="点击这里给我发消息">{$userList[$item['uid']]['nickname']}</a>
		              {elseif $userList[$item['uid']]['mobile']}
		              {$userList[$item['uid']]['mobile']}
		              {else}
		              {$item['uid']}
		              {/if}
		           </td>
		           <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
		           <td>{$item['gmt_modify']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
		           <td>
		              {if $profile && $profile['basic']['uid'] == $item['uid']}
		              <a href="javascript:void(0)">修改</a>
		              <a href="javascript:void(0)">删除</a>
		              {/if}
		              <a href="javascript:void(0);">点击完成交易</a>
		              <a href="javascript:void(0);" data-url="{site_url('trade/goods_done')}">复制</a>
		           </td>
		        <tr>
		        {/foreach}
		    </tbody>
		 </table>
	     <div>{include file="common/pagination.tpl"}</div>
    </form>
{include file="my/my_footer.tpl"}

