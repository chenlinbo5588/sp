    {include file="common/jquery_validation.tpl"}
    <div id="goodsCodeDlg" title="{#goods_slot#}{#goods_code#}信息配置" style="display:none;">
        <div class="loading_bg" style="display:none;">发送中...</div>
        <form id="slotForm" action="{site_url('inventory/slot_gc')}" method="post">
            <input type="hidden" name="slot_id" value="{$slotId}"/>
	        <table class="fulltable noborder">
	            <tr><td><input type="text" class="at_txt" name="goods_code" value="" placeholder="字母数字下划线、破折号，最长10个字符"/></td></tr>
	            <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="保存"/></td></tr>
	        </table>
        </form>
    </div>
    <div id="titleDlg" title="{#goods_slot#}信息配置" style="display:none;">
        <div class="loading_bg" style="display:none;">发送中...</div>
        <form id="slotTitleForm" action="{site_url('inventory/slot_title')}" method="post">
            <input type="hidden" name="slot_id" value="{$slotId}"/>
            <table class="fulltable noborder">
                <tr><td><input type="text" class="at_txt" name="title" value="" placeholder="货品个名称，最长10个字"/></td></tr>
                <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="保存"/></td></tr>
            </table>
        </form>
    </div>
