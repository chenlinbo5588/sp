{include file="common/main_header.tpl"}
  {config_load file="worker.conf"}
  {include file="common/sub_nav.tpl"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {/if}
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2 mgbottom">
      <tbody>
      	{include file="staff/detail_common.tpl"}
        <tr>
          <td colspan="2">{#avatar_photo#}:</td>
        </tr>
        <tr class="noborder">
          <td colspan="2">
          	<input type="hidden" name="avatar" value="{$info['avatar_b']}" id="avatar"/>
          	<div>
          		<img id="view_img" src="{if $info['avatar_m']}{resource_url($info['avatar_m'])}{/if}"/>
          	</div>
          </td>
        </tr>
        <tr>
          <td colspan="2">{#exp_photo#}:</td>
        </tr>
        <tr>
       		<td colspan="2">
       			<ul id="thumbnails" class="thumblists">
       			{foreach from=$fileList item=item}
       			<li id="{$item['image_aid']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['image_aid']}" /><div class="size-64x64"><span class="thumb"><i></i><a class="fancybox" href="{resource_url($item['image_b'])}" data-fancybox-group="gallery"><img src="{resource_url($item['image_b'])}" alt="" width="64px" height="64px"/></a></span></div></li>
       			{/foreach}
       			</ul>
       		</td>
       	</tr>
        <tr class="noborder">
          <td colspan="2"><label>{#bz#}: </label>{form_error('remark')}</td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">{$info['remark']|escape}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label>{#verify#}: {$statusConfig[$info['status']]}</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label>审核{#bz#}: </label></td>
        </tr>
        <tr class="noborder">
        	<td colspan="2" >
				<textarea name="reason" style="width:100%;height:100px;">{$info['reason']|escape}</textarea>
			</td>
        </tr>
      </tbody>
    </table>
    <div class="fixedOpBar">
    	{if $statusConfig[$info['status']] != '已审核'}<input type="submit" name="tijiao" value="审核通过" class="msbtn"/>{/if}
    	<input type="submit" name="tijiao" value="退回" class="msbtn"/>
    	{if $gobackUrl}
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    	{/if}
    </div>
  </form>
  <div id="avatarDlg"></div>
  
  {include file="common/fancybox.tpl"}
  <script type="text/javascript">
  	var province_idcard = {$province_idcard},
  		cutUrl = '{admin_site_url($moduleClassName|cat:"/pic_cut")}?mod={$moduleClassName}&id={$info['id']}',
  		uploadUrl = '{admin_site_url($moduleClassName|cat:"/addimg")}?mod={$moduleClassName}&id={$info['id']}';
	
	$(function(){
		$( ".datepicker" ).datepicker();
		$('.fancybox').fancybox();
		
		bindAjaxSubmit("#infoform");
	});
	
	{if $redirectUrl}
		setTimeout(function(){
			location.href="{$redirectUrl}";
		},2000);
	{/if}
  </script>
{include file="common/main_footer.tpl"}