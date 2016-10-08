{include file="common/my_header.tpl"}
	{config_load file="hp.conf"}
	<form action="{site_url($uri_string)}" method="post" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="goods_search">
             <ul class="search_con">
                <li class="first"><textarea name="gc" placeholder="输入货号，每行一个货号或者单行按逗号分隔，一次最多可同时50个">{$smarty.post.gc}</textarea></li>
                <li>
                    <label class="ftitle">名称</label>
                    <input type="text" class="mtxt" name="gn" value="{$smarty.post.gn}"/>
                </li>
                <li>
                    <label class="ftitle">尺寸</label>
                    <input type="text" name="s1" class="stxt" value="{$smarty.post.s1}" placeholder="尺寸下限"/>
                    <input type="text" name="s2" class="stxt" value="{$smarty.post.s2}" placeholder="尺寸上限(CN)"/>
                </li>
                <li>
                    <label class="ftitle">性别</label>
                    <label><input type="radio" name="sex" value="0" {if $smarty.post.sex == 0}checked{/if}/>不限</label>
                    <label><input type="radio" name="sex" value="1" {if $smarty.post.sex == 1}checked{/if}/>男</label>
                    <label><input type="radio" name="sex" value="2" {if $smarty.post.sex == 2}checked{/if}/>女</label>
                </li>
                <li>
                    <label class="ftitle">价格范围</label>
                    <input type="text" name="pr1" class="stxt" value="{if $smarty.post.pr1}{$smarty.post.pr1}{/if}" placeholder="最低价"/>
                    <input type="text" name="pr2" class="stxt" value="{if $smarty.post.pr2}{$smarty.post.pr2}{/if}" placeholder="最高价"/>
                </li>
                {*
                <li>
                    <label class="ftitle">剩余数量</label>
                    <input type="text" name="cn1" class="stxt" value="{if $smarty.post.cn1}{$smarty.post.cn1}{/if}" placeholder="最低剩余"/>
                    <input type="text" name="cn2" class="stxt" value="{if $smarty.post.cn2}{$smarty.post.cn2}{/if}" placeholder="最高剩余"/>
                </li>
                *}
                <li>
                    <input class="master_btn" type="submit" name="search" value="查询"/>
                </li>
             </ul>
             <div class="searchtip" id="gctip" style="display:none;">
                <div class="tip">单行模式: 229284000,884926-006,148777000</div>
                <div class="tip">多行莫斯: <br/>229284000<br/>884926-006<br/>148777000<br/>....</div>
            </div>
	    </div>
        <div>{include file="common/pagination.tpl"}</div>
        <table class="fulltable">
            <thead>
                <tr>
                    <th>求货序号</th>
                    <th>{#goods_code#}</th>
                    <th>{#goods_name#}</th>
                    <th>{#goods_color#}</th>
                    <th>{#goods_size#}</th>
                    <th>{#quantity#}</th>
                    <th>{#sex#}</th>
                    <th>{#price_max#}</th>
                    <th>发布人</th>
                    <th>发布时间</th>
                    <th>最后更新时间</th>
                    <th>操作</th>
                </tr>
            </thead>
	        <tbody>
		        {foreach from=$list item=item}
		        <tr>
		           <td>{$item['goods_id']}</td>
		           <td>{$item['goods_code']|escape}</td>
		           <td>{$item['goods_name']|escape}</td>
		           <td>{$item['goods_color']|escape}</td>
		           <td>{$item['goods_size']}</td>
		           <td>{$item['quantity']}</td>
		           <td>{if $item['sex'] == 1}男{else}女{/if}</td>
		           <td>{$item['price_max']}</td>
		           <td>
		              {if $userList[$item['uid']]['qq']}
		              <a target="_blank" class="qqchat" href="http://wpa.qq.com/msgrd?v=3&uin={$userList[$item['uid']]['qq']}&site=qq&menu=yes" alt="点击这里给我发消息" title="点击这里给我发消息">{$userList[$item['uid']]['qq']}</a>
		              <span>QQ:{$userList[$item['uid']]['qq']}</span>
		              {elseif $userList[$item['uid']]['mobile']}
		              <span>手机:{$userList[$item['uid']]['mobile']}</span>
		              {else}
		              {$item['uid']}
		              {/if}
		           </td>
		           <td>{time_tran($item['gmt_create'])}</td>
		           <td>{time_tran($item['gmt_modify'])}</td>
		           <td>
		              {if $profile && $profile['basic']['uid'] == $item['uid']}
		              <a href="javascript:void(0)">修改</a>
		              <a href="javascript:void(0)">删除</a>
		              {/if}
		           </td>
		        <tr>
		        {/foreach}
		    </tbody>
		 </table>
	     <div>{include file="common/pagination.tpl"}</div>
    </form>
{include file="common/my_footer.tpl"}

