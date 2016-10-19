{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    {if $currentGroupId == 2}
    <div class="panel pd20 warnbg">
        <span>当前库存不可用,您的账户尚未进行卖家审核,<a class="warning" href="{site_url('my/seller_verify')}">马上去认证</a></span>
    </div>
    {else}
        <div class="basic-information-list">
          <h2 class="information-list-title"><em>&nbsp;批量导入货品记录</em></h2>
          {$stepHTML}
          <div class="hp-import-box">
            {form_open(site_url($uri_string),"id='inventoryForm'")}
            <input class="w50pre" id="file_upload" type="file" name="hpfile" />
            <input type="submit" name="tijiao" value="下一步" style="display:none;"/>
          </div>
          <div class="w-tixing clearfix"><b>温馨提醒：</b>
            <p>1、上传文件格式支持xls和xlsx，大小不超过2MB。</p>
            <p>2、系统会按照预定模板扫描您的文件，并导入数据。<a href="{resource_url('example/hp_record.xls')}" target="_blank">下载模版</a></p>
            <p>3、每次最多可以导入500条解析记录，超出的部分将不会导入。</p>
            <p>4、如果原来库存已经存在重新导入将进行更新覆盖操作， </p>
            <p>5、货号和尺码唯一确定一个货品，即同一个货号下的不同尺寸被认为是不同的货品，您总的库存货品数量不能超过1000条</p>
          </div>
        </div>
        {if $currentHpCnt == 0}
        
        {include file="common/uploadify.tpl"}
        <script type="text/javascript">
        $('#file_upload').uploadify({
            'fileTypeDesc' : 'Excel文件',
            'buttonText' : '选择Excel文件',
            'fileTypeExts' : '*.xls;*.xlsx',
            'formData'     : {
                
            },
            'swf'      : "{resource_url('js/uploadify/uploadify.swf')}",
            'uploader' : "{site_url('inventory/upload')}",
            'onUploadSuccess' : function(file, data, response) {
                console.log(data);
                var json = $.parseJSON(data);
                if(/成功/.test(json.message)){
                    $("input[name=tijiao]").show();
                }
            }
        });
        </script>
        {else}
        
        <form action="{site_url($uri_string)}" method="post" id="formSearch">
	        <input type="hidden" name="page" value=""/>
	        <div class="goods_search">
	             {if $secondsElpse > 0}
	             <div><input type="submit" class="master_btn" value="刷新库存"/>{*&nbsp;<input type="button" class="master_btn" value="匹配我的库存"/>*}<strong class="pd5">还剩下{$secondsElpse}秒,库存将自动过期</strong></div>
	             {else}
	             <div><input type="submit" class="master_btn" value="刷新库存"/>{*&nbsp;<input type="button" class="master_btn" value="匹配我的库存"/>*}<strong class="pd5">您得库存已过期</strong></div>
	             {/if}
	             <div class="hightlight pd5">库存默认过期时间为3个小时，过期以后需要可以进行手动刷新，库存刷新以后便可以获得平台后台自动匹配的消息提醒</div>
	             <div class="tip pd5">系统默认分类给了那您10个{#goods_slot#}，每个{#goods_slot#}可以添加50个不同尺寸的货品,如您需要更多{#goods_slot#}，请在网站下发找到我们的联系方式并与我们取得沟通。</div>
	             <ul class="slot_list clearfix">
	                {foreach from=$list['slot_config'] item=item}
	                <li class="slot_item" data-id="{$item['id']}">
	                    <div class="title"><a href="{site_url('inventory/slot_edit?id='|cat:$item['id'])}" title="添加货品到货柜"><strong>{$item['title']|escape}</strong><span class="hightlight">({$item['cnt']}/{$item['max_cnt']})</span></a>&nbsp;<a href="javascript:void(0);" data-title="{$item['title']|escape}" class="mtitle">改名</a></div>
	                    <div class="goods_code">{if $item['goods_code']}{#goods_code#}:{$item['goods_code']}{else}<a class="setgc" href="javascript:void(0);" data-title="{$item['title']|escape}">设置货号</a>{/if}</div>
	                </li>
	                {/foreach}
	             </ul>
	        </div>
	    </form>
	    <div id="confirmOf">
	        <div class="loading_bg" style="display:none;">发送中...</div>
	        <div class="confirmtitle"></div>
	    </div>
	    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
	    <script>
	        var setgcUrl = "{site_url('inventory/slot_gc')}";
	    </script>
	    {include file="./dlg_code.tpl"}
	    <script type="text/javascript" src="{resource_url('js/my/slot_list.js')}"></script>
        
        {/if}
    {/if}
{include file="common/my_footer.tpl"}
