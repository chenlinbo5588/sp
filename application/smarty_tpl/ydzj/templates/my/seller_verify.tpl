{include file="common/my_header.tpl"}
    {$stepHTML}
	<div class="w-tixing clearfix"><b>温馨提醒：</b>
	    <p>通过卖家认证之后,将可以获得后台实时的匹配提醒</p>
	  </div>
	{include file="common/fancybox.tpl"}
	{if $step == 1}
	{form_open_multipart(site_url($uri_string),"id='sellerForm'")}
	<input type="hidden" name="step" value="2"/>
	<input type="hidden" name="img_b" value="{$info['img_b']}"/>
	<input type="hidden" name="img_m" value="{$info['img_m']}"/>
	<table class="fulltable style1">
	    <tbody>
			<tr>
				<td class="w120"><label>网店链接</label></td>
				<td><input class="w50pre" type="text" name="store_url" value="{set_value('store_url')}" placeholder="请输入网店链接地址"/>{form_error('store_url')}</td>
			</tr>
			<tr>
				<td class="w120"><label>卖家最近交易流水</label></td>
				<td>
				    <input id="file_upload" type="file" name="trade_pic" />{$file_error}<span>请上传尺寸JPG格式的最近交易流水图片,最小尺寸400x400</span>
				</td>
			</tr>
			<tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="master_btn" name="tijiao" value="下一步"/></td>
            </tr>
		</tbody>
	</table>
	</form>
	{include file="common/jquery_validation.tpl"}
	<script type="text/javascript">
	   $(function() {
            $("#sellerForm").validate({
                rules : {
                    store_url:{
                        required:true
                    }
                }
            });
        });
    </script>
    {elseif $step == 2}
    {form_open(site_url($uri_string),"id='sellerForm'")}
    <input type="hidden" name="step" value="3"/>
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
				       <div class="storeurl">网店链接:<a href="{$info['store_url']}" target="_blank">{$info['store_url']}</a></div>
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
        <span>{if $verfiyInfo['verify_result'] == 1}尊敬的<strong>{$profile['basic']['username']}</strong>用户，您已经认证成功，您现在可以去<a class="hightlight"  href="{site_url('inventory/index')}">维护库存</a>，您可以收到后台求货自动匹配的消息提醒。
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

