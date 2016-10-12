    {include file="common/jquery_validation.tpl"}
    <div id="goodsCodeDlg" title="{#goods_slot#}信息配置" style="display:none;">
        <div class="loading_bg" style="display:none;">发送中...</div>
        <form id="slotForm" action="{site_url('inventory/slot_gc')}" method="post">
            <input type="hidden" name="slot_id" value="{$slotId}"/>
	        <table class="fulltable noborder">
	            <tr><td><input type="text" class="at_txt" name="goods_code" value="" placeholder="请输入{#goods_code#}"/></td></tr>
	            <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="保存"/></td></tr>
	        </table>
        </form>
    </div>
