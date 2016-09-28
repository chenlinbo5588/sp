{include file="./my_header.tpl"}
	<div class="w-step-row">
		<div class="w-step4 {if $step > 1}w-step-past{else if $step == 1} w-step-cur{/if}">上传认证资料</div>
		<div class="w-step4 {if $step > 2}w-step-past-past{else if $step == 2}w-step-past-cur{else}w-step-cur-future{/if}">确认提交</div>	
		<div class="w-step4 w-step-future-future">审核</div>		
		<div class="w-step4 {if $step < 4}w-step-future-future{else}w-step-past-cur{/if}">审核结果</div>
	</div>
	<div class="muted">通过卖家认证之后,将可以获得后台实时的匹配提醒</div>
	
	
	{form_open_multipart(site_url($uri_string),"id='sellerForm'")}
	<input type="hidden" name="step" value="{$step}"/>
	<input type="hidden" name="file_id" value=""/>
	<input type="hidden" name="img_b" value=""/>
	<input type="hidden" name="img_m" value=""/>
	<table class="fulltable style1">
	    <tbody>
			<tr>
				<td class="w120"><label>网店链接</label></td>
				<td><input class="w50pre" type="text" name="store_url" value="{set_value('store_url')}" placeholder="请输入网店链接地址"/>{form_error('store_url')}</td>
			</tr>
			<tr>
				<td class="w120"><label>卖家最近交易流水</label></td>
				<td>
				    <input class="w50pre" id="file_upload" type="file" name="trade_pic" /><span>请上传尺寸JPG格式的最近交易流水图片,最小尺寸400x400</span>
				</td>
			</tr>
			<tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="master_btn" name="tijiao" value="下一步"/></a></td>
            </tr>
		</tbody>
	</table>
	
	<div class="trade_previw">
	   <h5>上传成功后,点击图片查询大图</h5>
	   <div id="prev" title="点击查询大图">
	</div>
	{include file="common/jquery_validation.tpl"}
	{include file="common/uploadify.tpl"}
	{include file="common/fancybox.tpl"}
	<script type="text/javascript">
	   $(function() {
            $('#file_upload').uploadify({
                'fileTypeDesc' : '图片文件',
                'buttonText' : '选择图片文件',
                'fileTypeExts' : '*.jpg;*.png',
                'formData'     : {
                    'min_width' : 400,
                    'min_height' : 400
                },
                'swf'      : "{resource_url('js/uploadify/uploadify.swf')}",
                'uploader' : "{site_url('my/trade_upload')}",
                'onUploadSuccess' : function(file, data, response) {
                    var json = $.parseJSON(data);
                    $("input[name=file_id]").val(json.data.id);
                    $("input[name=img_b]").val(json.data.b);
                    $("input[name=img_m]").val(json.data.m);
                    $("#prev").html('<a class="fancybox-thumbs" data-fancybox-group="thumb" href="' + json.data.b + '"><img src="' + json.data.m + '" alt="" /></a>');
		        }
            });
            
            $('.fancybox-thumbs').fancybox({
                prevEffect : 'none',
                nextEffect : 'none',
                closeBtn  : false,
                arrows    : false,
                nextClick : true,
                helpers : {
                    thumbs : {
                        width  : 50,
                        height : 50
                    }
                }
            });
            
            $("#sellerForm").validate({
                rules : {
                    store_url:{
                        required:true,
                        url:true
                    },
                }
            });
        });
    </script>
{include file="./my_footer.tpl"}

