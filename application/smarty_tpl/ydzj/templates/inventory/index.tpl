{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    {if $currentGroupId == 2}
    <div class="panel pd20 warnbg">
        <span>当前库存不可用,您的账户尚未进行卖家审核,<a class="warning" href="{site_url('my/seller_verify')}">马上去认证</a></span>
    </div>
    {else}
        {if $currentHpCnt == 0}
        {include file="./import.tpl"}
        {else}
        <div>{if $last_update}上次更新时间：{time_tran($last_update)}{/if}
        {if $isExpired}
            {if $isMobile}
            <a id="reative" class="master_btn" href="javascript:void(0);">重新激活库存</a>
            {else}
            <a class="master_btn" href="{site_url('inventory/import')}">重新激活库存</a>
            {/if}
        </div>
        {/if}
        <div class="w-tixing clearfix"><b>温馨提醒：</b>
            <p>库存更新通过导入文件方式更新. </p>
            <p>库存更新冻结时间 5 分钟.</p>
            <p>库存自动失效时间 3 小时.</p>
          </div>
        <form action="{site_url($uri_string)}" method="get" id="formSearch">
	        <input type="hidden" name="page" value=""/>
	        <table class="fulltable">
	            <thead>
	                <tr>
	                	<th>{#goods_code#}</th>
	                    <th>{#goods_name#}</th>
	                    <th>{#goods_color#}</th>
	                    <th>{#goods_size#}</th>
	                    <th>{#sex#}</th>
	                    <th>{#inventorynum#}</th>
	                    <th>{#price_min#}</th>
	                </tr>
	            </thead>
	            <tbody>
	                {foreach from=$list key=key item=item}
	                <tr>
	                   <td>{$item['goods_code']|escape}</td>
	                   <td>{$item['goods_name']|escape}</td>
	                   <td>{$item['goods_color']|escape}</td>
	                   <td>{$item['goods_size']}</td>
	                   <td>{$item['sex']|escape}</td>
	                   <td>{$item['quantity']}</td>
	                   <td>{$item['price_min']}</td>
	                <tr>
	                {foreachelse}
	                <tr><td colspan="14">找不到相关记录</td></tr>
	                {/foreach}
	            </tbody>
	        </table>
		    <div>{include file="common/pagination.tpl"}</div>
	    </form>
	    
	    <script>
	       $(function(){
	           $.loadingbar({ urls: [ new RegExp('{site_url('inventory/reactive')}') ],text:"操作中,请稍后..."});
	           
	           $("#reative").bind("click",function(){
	               $.post("{site_url('inventory/reactive')}", {  },function(resp){
                        
                        if(!/成功/.test(resp.message)){
                            showToast('error',resp.message);
                        }else{
                            showToast('success',resp.message);
		                    if(typeof(resp.data.redirectUrl) != "undefined"){
		                        setTimeout(function(){
		                            location.href = resp.data.redirectUrl;
		                        },500);
		                    }
                        }
                 
                   }, "json");
	           });
	       });
	    
	    </script>
        {/if}
    {/if}
{include file="common/my_footer.tpl"}
