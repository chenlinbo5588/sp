{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    <div class="tabin_screen">
        <ul class="clearfix">
            <li {if $uri_string == 'my_req/recent'}class="scr_cur"{/if}><a href="{site_url('my_req/recent')}">最近3日内求货</a></li>
            <li {if $uri_string == 'my_req/history'}class="scr_cur"{/if}><a href="{site_url('my_req/history')}">历史求货</a></li>
        </ul>
    </div>
    <form action="{site_url($uri_string)}" method="post" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="goods_search">
        	 {if $uri_string == 'my_req/recent'}<textarea name="gc" placeholder="输入{#goods_code#}，每行一个货号或者单行按逗号分隔，一次最多可同时50个">{$smarty.post.gc}</textarea>{/if}
             <ul class="search_con clearfix">
                <li>
                    <label class="ftitle">{#pub_date#}</label>
                    <input type="text" name="sdate" class="w72 datepicker" value="{if $smarty.post.sdate}{$smarty.post.sdate}{/if}" placeholder="日期开始"/>
                    <input type="text" name="edate" class="w72 datepicker" value="{if $smarty.post.edate}{$smarty.post.edate}{/if}" placeholder="日期结束"/>
                </li>
                {if $uri_string == 'my_req/recent'}
                <li>
                    <label class="ftitle">{#isexpired#}</label>
                    {foreach from=$isExpired item=item key=key}
                    <label><input type="radio" name="isexpired" value="{$key}" {if $smarty.post.isexpired == $key}checked{/if}/>{$item}</label>
                    {/foreach}
                </li>
                <li>
                    <label class="ftitle">{#goods_name#}</label>
                    <input type="text" class="mtxt" name="gn" value="{$smarty.post.gn}"/>
                </li>
                <li>
                    <label class="ftitle">{#goods_size#}</label>
                    <input type="text" name="s1" class="stxt" value="{$smarty.post.s1}" placeholder="尺寸下限"/>
                    <input type="text" name="s2" class="stxt" value="{$smarty.post.s2}" placeholder="尺寸上限"/>
                </li>
                <li>
                    <label class="ftitle">{#sex#}</label>
                    <label><input type="radio" name="sex" value="0" {if $smarty.post.sex == 0}checked{/if}/>不限</label>
                    <label><input type="radio" name="sex" value="1" {if $smarty.post.sex == 1}checked{/if}/>男</label>
                    <label><input type="radio" name="sex" value="2" {if $smarty.post.sex == 2}checked{/if}/>女</label>
                </li>
                <li>
                    <label class="ftitle">{#price_max#}</label>
                    <input type="text" name="pr1" class="stxt" value="{if $smarty.post.pr1}{$smarty.post.pr1}{/if}" placeholder="下限"/>
                    <input type="text" name="pr2" class="stxt" value="{if $smarty.post.pr2}{$smarty.post.pr2}{/if}" placeholder="上限"/>
                </li>
                {/if}
                <li>
                    <input class="master_btn" type="submit" name="search" value="查询"/>
                </li>
             </ul>
             {include file="hp/code_tip.tpl"}
	        <table class="fulltable">
	            <thead>
	                <tr>
	                	<th class="w72"><label><input type="checkbox" class="checkall" name="hpid" /><strong>全选/取消</strong></label></th>
	                    <th>{#goods_id#}</th>
	                    <th>{#goods_code#}</th>
	                    <th>{#goods_name#}</th>
	                    <th>{#goods_color#}</th>
	                    <th>{#goods_size#}</th>
	                    <th>{#quantity#}</th>
	                    <th>{#sex#}</th>
	                    <th>{#accept#}{#price_max#}</th>
	                    <th>{#status#}</th>
	                    <th>{#pub_date#}</th>
	                    <th>{#mtime#}</th>
	                    <th>{#op#}</th>
	                </tr>
	            </thead>
	            <tbody>
	                {foreach from=$list item=item}
	                <tr id="row{$item['goods_id']}">
	                   <td><input type="checkbox" name="id[]" group="hpid" value="{$item['goods_id']}"/></td>
	                   <td>{$item['goods_id']}</td>
	                   <td>{$item['goods_code']|escape}</td>
	                   <td>{$item['goods_name']|escape}</td>
	                   <td>{$item['goods_color']|escape}</td>
	                   <td>{$item['goods_size']}</td>
	                   <td>{$item['quantity']}</td>
	                   <td>{if $item['sex'] == 1}男{else}女{/if}</td>
	                   <td>{$item['price_max']}</td>
	                   <td>{if ($reqtime - $item['gmt_modify']) >= 5400}<span class="warning">已过期</span>{else}正常{/if}</td>
	                   <td>{time_tran($item['gmt_create'])}</td>
	                   <td>{time_tran($item['gmt_modify'])}</td>
	                   <td>
	                   	  {if $uri_string == 'my_req/recent'}
	                      <a href="javascript:void(0)">更新</a>
	                      <a class="delete" href="javascript:void(0)">删除</a>
	                      {elseif $uri_string == 'my_req/history'}
	                      <a class="delete" href="javascript:void(0)" data-url="{site_url('my_req/history_del')}">删除</a>
	                      {/if}
	                   </td>
	                <tr>
	                {foreachelse}
	                <tr><td colspan="12">找不到相关记录</td></tr>
	                {/foreach}
	            </tbody>
	        </table>
	        <tfoot>
		      <tr>
		          <td colspan="12">
		              <div class="pd5">
		              {if $uri_string == 'my_req/recent'}<input type="button" class="action updateBtn" data-checkbox="id[]" data-title="重新激活" data-url="{site_url('my_req/reactive')}" name="batchUpdate" value="重新激活"/>{/if}
		              {if $uri_string == 'my_req/history'}<input type="button" class="action" name="pub" value="重新发布"/>{/if}
		              <input type="button" class="action deleteBtn" data-checkbox="id[]" data-title="删除" data-url="{site_url('my_pm/delete')}" name="delete" value="删除" />
		              </div>
		          </td>
		      </tr>
		    </tfoot>
		    <div>{include file="common/pagination.tpl"}</div>
	    </div>
    </form>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script>
        $(function(){
        	$( ".datepicker" ).datepicker({
				 numberOfMonths: 1,
				 showButtonPanel: false,
				 changeMonth: true,
				 changeYear: true,
				 minDate: 0
			});
			
			
			var successCallback = function(ids,json){
				if(check_success(json.message)){
					showToast('success',json.message);
				}else{
					showToast('error',json.message);
				}
			}
	
            bindDeleteEvent();
            {if $uri_string == 'my_req/recent'}bindOpEvent("input.updateBtn",successCallback);{/if}
        });
    </script>
    {include file="common/jquery_validation.tpl"}
{include file="common/my_footer.tpl"}
