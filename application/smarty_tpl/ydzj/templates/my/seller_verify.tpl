{include file="common/my_header.tpl"}
    {$stepHTML}
	<div class="hightlight">通过卖家认证之后,将可以获得后台实时的匹配提醒</div>
	{include file="common/fancybox.tpl"}
	{if $step == 1}
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
				    {form_error('img_b')}
				</td>
			</tr>
			<tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="master_btn" name="tijiao" value="下一步"/></td>
            </tr>
		</tbody>
	</table>
	</form>
	<div class="trade_previw">
	   <h5>上传成功后,点击图片可查看大图</h5>
	   <div id="prev" title="点击查看大图"></div>
	</div>
	{include file="common/jquery_validation.tpl"}
	{include file="common/uploadify.tpl"}
	
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
                    $("#prev").html('<a class="fancybox" href="' + json.data.b + '"><img src="' + json.data.m + '" alt="" /></a>');
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
    {elseif $step == 2}
    {form_open(site_url($uri_string),"id='sellerForm'")}
    <input type="hidden" name="step" value="{$step}"/>
    <input type="hidden" name="store_url" value="{$info['store_url']}"/>
    <input type="hidden" name="source_pic" value="{$info['source_pic']}"/>
    <input type="hidden" name="trade_pic" value="{$info['trade_pic']}"/>
	<table class="fulltable style1">
        <tbody>
            <tr>
                <td colspan="2">
                    <div class="trade_previw">
				       <h5>卖家最近交易流水,点击图片可查看大图</h5>
				       <a class="fancybox" href="{resource_url($info['source_pic'])}"><img src="{resource_url($info['trade_pic'])}"/></a>
				       <div class="storeurl"><a href="{$info['store_url']}" target="_blank">网店链接:{$info['store_url']}</a></div>
				       <div><input type="submit" class="master_btn" name="tijiao" value="确认无误,下一步"/></div>
				    </div>
                </td>
            </tr>
        </tbody>
    </table>
	</form>
	{elseif $step == 3}
	<div class="panel pd20 passbg">
        <span>你的认证信息已提交,我们将在24小时内进行审核,请您耐心等待.</span>
    </div>
    {elseif $step == 4}
    <div class="panel pd20{if $verfiyInfo['verify_result'] == 1} passbg{else} warnbg{/if}">
        <span>{if $verfiyInfo['verify_result'] == 1}尊敬的<strong>{$profile['basic']['username']}</strong>用户，您已经认证成功，您现在可以去<a class="hightlight"  href="{site_url('inventory/index')}">维护库存</a>，您可以收到后台去求货自动匹配的消息提醒。
        {else}很抱歉，你的认证未通过审核，未审核原因:<span class="tip_error">{$verfiyInfo['verify_remark']|escape}</span>&nbsp;<a class="hightlight" href="{site_url('my/seller_verify?retry=yes')}">重新提交审核信息</a>{/if}</span>
    </div>
	{/if}
	<script>
	   $(function() {
			$('.fancybox').fancybox({
		        closeBtn  : true
		    }); 
	   });  
	</script>
{include file="common/my_footer.tpl"}

