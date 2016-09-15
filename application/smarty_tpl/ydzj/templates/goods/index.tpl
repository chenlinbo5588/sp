{include file="my/my_header.tpl"}
	<form action="{site_url('goods/index')}" method="post" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="goods_search">
	        <table class="noborder">
	            <colgroup>
	                <col style="width:70%"/>
	                <col style="width:30%"/>
	            </colgroup>
	            <tbody>
	                <tr>
	                    <td>
	                        <textarea name="gc" placeholder="输入货号，每行一个货号, 也可以按逗号分隔，一次最多可同时50个">{$smarty.post.gc}</textarea>
	                    </td>
	                    <td>
	                        <div class="searchtip fl">
				                <div class="tip">单行模式: 229284000,884926-006,148777000</div>
				                <div class="tip">多行莫斯: <br/>229284000<br/>884926-006<br/>148777000<br/>....</div>
				            </div>
	                    </td>
	                </tr>
	            </tbody>
	         </table>
	         <table class="noborder">
	            <colgroup>
	                <col style="width:10%"/>
	                <col style="width:40%"/>
	                <col style="width:10%"/>
	                <col style="width:40%"/>
	            </colgroup>
	            <tbody>
	                <tr>
	                    <td><label>名称</label></td><td><input type="text" name="gname" value="{$smarty.post.gname}"/></td>
	                    <td><label>性别</label></td>
	                    <td>
	                        <label><input type="radio" name="sex" value="0" {if $smarty.post.sex == 0}checked{/if}/>不限</label>
	                        <label><input type="radio" name="sex" value="1" {if $smarty.post.sex == 1}checked{/if}/>男</label>
	                        <label><input type="radio" name="sex" value="2" {if $smarty.post.sex == 2}checked{/if}/>女</label>
	                    </td>
	                </tr>
	                <tr>
	                    <td><label>尺寸</label></td>
	                    <td>
	                        <input type="text" name="s1" value="{$smarty.post.s1}" placeholder="尺寸下限(CN) 空标志无下限"/>
	                        <input type="text" name="s2" value="{$smarty.post.s2}" placeholder="尺寸上限(CN) 空标志无上限"/>
	                    </td>
	                    <td><label>期望价格范围</label></td>
	                    <td>
	                        <input type="text" name="pr1" value="{$smarty.post.pr1}" placeholder="最低价，留空不限定"/>
	                        <input type="text" name="pr2" value="{$smarty.post.pr2}" placeholder="最高价，留空不限定"/>
	                    </td>
	                </tr>
	                <tr>
	                    <td><label>颜色</label></td>
	                    <td>
	                       <label></label>
	                    </td>
	                </tr>
	                <tr>
	                    <td><label>剩余数量</label></td>
	                    <td><input type="text" name="cnum" value="{if $smarty.post.cnum}{$smarty.post.cnum}{else}1{/if}" placeholder="默认1"/></td>
	                </tr>
	                <tr>
	                    <td><input class="master_btn" type="submit" name="search" value="查询"/></td>
	                </tr>
	            </tbody>
	        </table>
	    </div>
        <div class="warning">用户在线沟通成交后，请求货信息发布方点击成交链接完成交易.为了平台的信息的准确性和实时性。请在交易完成后及时点击完成交易。</div>
        <div>{include file="common/pagination.tpl"}</div>
        <table class="fulltable">
            <thead>
                <tr>
                    <th>求货序号</th>
                    <th>货品名称</th>
                    <th>货号</th>
                    <th>颜色</th>
                    <th>尺码(CN)</th>
                    <th>求购数量(剩余)</th>
                    <th>性别</th>
                    <th>期望求购价格范围</th>
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
		           <td>{$item['goods_color']|escape}</td>
		           <td>{$item['goods_size']}</td>
		           <td>{$item['quantity']}&nbsp;({$item['cnum']})</td>
		           <td>{$item['sex']}</td>
		           <td>{$item['price_min']} ~ {$item['price_max']}</td>
		           <td>
		              {if $userList[$item['uid']]['qq']}
		              <a target="_blank" class="qqchat" href="http://wpa.qq.com/msgrd?v=3&uin={$userList[$item['uid']]['qq']}&site=qq&menu=yes" alt="点击这里给我发消息" title="点击这里给我发消息">{$userList[$item['uid']]['nickname']}</a>
		              {elseif $userList[$item['uid']]['mobile']}
		              {$userList[$item['uid']]['mobile']}
		              {else}
		              {$item['uid']}
		              {/if}
		           </td>
		           <td>{$item['gmt_create']|date_format:"%m-%d %H:%M"}</td>
		           <td>{$item['gmt_modify']|date_format:"%m-%d %H:%M"}</td>
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

