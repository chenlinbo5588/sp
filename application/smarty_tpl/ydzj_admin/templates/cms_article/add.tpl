{include file="common/main_header_navs.tpl"}
{config_load file="article.conf"}
  {if $info['id']}
  {form_open_multipart(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="article_form"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(site_url($uri_string),'id="article_form"')}
  {/if}
  	<table class="table tb-type2 nobdb mgbottom">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">标题:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['article_title']|escape}" name="article_title" id="article_title" class="txt"></td>
          <td class="vatop tips">{form_error('article_title')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="ac_id">所属分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="ac_id" id="articleClassId">
	          <option value="">请选择...</option>
	          {foreach from=$articleClassList item=item}
	          <option {if $info['ac_id'] == $item['id']}selected{/if} value="{$item['id']}">{str_repeat('......',$item['level'])}{$item['name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('ac_id')} 文章的分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="article_origin">{#article_origin#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['article_origin']|escape}" name="article_origin" id="article_origin" class="txt"></td>
          <td class="vatop tips">{form_error('article_origin')} 文章来源</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="origin_address">{#origin_address#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['origin_address']|escape}" name="origin_address" id="origin_address" class="txt"></td>
          <td class="vatop tips">{form_error('origin_address')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="jump_url">{#jump_url#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['jump_url']}" name="jump_url" id="jump_url" class="txt"></td>
          <td class="vatop tips">{form_error('jump_url')} 当填写&quot;链接&quot;后点击文章标题将直接跳转至填写的链接地址。链接格式请以http://开头</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['article_sort']}{$info['article_sort']}{else}255{/if}" name="article_sort" id="article_sort" class="txt"></td>
          <td class="vatop tips">{form_error('article_sort')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>文章封面</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            
            <div class="upload"><input type='text' readonly class="txt" name='image_url' id='image_url' value="{$info['image_url']}"/><input type="button" id="uploadButton" value="浏览" /></div>
            </td>
          <td class="vatop tips">{form_error('image_url')} jpg格式 最小尺寸 <strong class="warning">{$imageConfig['m']['width']}x{$imageConfig['m']['height']}</strong></td>
	    </tr>
	    <tr class="noborder">
	    	<td colspan="2"><div id="preview">{if $info['image_url']}<img src="{resource_url($info['image_url'])}" width="{$imageConfig['m']['width']}" height="{$imageConfig['m']['height']}"/><div><a href="javascript:delImg(this);">刪除</a></div>{/if}</div>
	    	</td>
	    </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">文章内容:</label>{form_error('content')}</td>
        </tr>
        <tr>
        	<td colspan="2" ><textarea id="content" name="content" style="width:100%;height:480px;visibility:hidden;">{$info['content']}</textarea></td>
        	{include file="common/ke.tpl"}
			<script type="text/javascript">
	            var editor1;
	            KindEditor.ready(function(K) {
	                editor1 = K.create('textarea[name="content"]', {
	                    uploadJson : '{admin_site_url("common/pic_upload")}?mod=article',
	                    filePostName:'Filedata',
	                    extraFileUploadParams:{ formhash: formhash },
	                    allowImageUpload : true,
	                    allowFlashUpload : false,
	                    allowMediaUpload : false,
	                    formatUploadUrl : false,
	                    allowFileManager : false,
	                    afterCreate : function() {
	                    	
	                    },
	                    afterChange : function() {
	                    	$("input[name=formhash]").val(formhash);
	                    },
	                    afterUpload : function(url,data) {
	                    	formhash = data.formhash;
		                }
	                });
	            });
	        </script>
        </tr>
        {*
        <tr>
          <td colspan="2" class="required"><label class="validation">{#keyword#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="keyword" value="{$info['keyword']|escape}" class="txt"/></td>
          <td class="vatop tips">{form_error('keyword')}</td>
        </tr>
        *}
        <tr>
          <td colspan="2" class="required"><label>文章作者:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="author" value="{$info['author']|escape}" class="txt"/></td>
          <td class="vatop tips">{form_error('author')}</td>
        </tr>
        {*
        <tr>
          <td colspan="2" class="required"><label>文章标签:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="article_tag" value="{$info['article_tag']|escape}" class="txt"/></td>
          <td class="vatop tips">{form_error('article_tag')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>{#commend_flag#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="commend_flag1" {if $info['commend_flag'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if} ><span>是</span></label>
            <label for="commend_flag0" {if $info['commend_flag'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if} ><span>否</span></label>
            <input id="commend_flag1" name="commend_flag" {if $info['commend_flag'] == 1}checked="checked"{/if} value="1" type="radio">
            <input id="commend_flag0" name="commend_flag" {if $info['commend_flag'] == 0}checked="checked"{/if} value="0" type="radio">
          </td>
          <td class="vatop tips">{form_error('commend_flag')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>{#comment_flag#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="comment_flag1" {if $info['comment_flag'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if} ><span>是</span></label>
            <label for="comment_flag0" {if $info['comment_flag'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if} ><span>否</span></label>
            <input id="comment_flag1" name="comment_flag" {if $info['comment_flag'] == 1}checked="checked"{/if} value="1" type="radio">
            <input id="comment_flag0" name="comment_flag" {if $info['comment_flag'] == 0}checked="checked"{/if} value="0" type="radio">
          </td>
          <td class="vatop tips">{form_error('comment_flag')}</td>
        </tr>
        *}
        <tr>
          <td colspan="2" class="required"><label class="validation">{#article_state#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="article_state">
          		{foreach from=$article_state key=key item=item}
          		<option value="{$key}" {if $info['article_state'] == $key}selected{/if}>{$item}</option>
          		{/foreach}
          	</select>
          </td>
          <td class="vatop tips">{form_error('article_state')}</td>
        </tr>
        {if $info['id']}
        <tr>
          <td colspan="2" class="required"><label>{#comment_count#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="comment_count" value="{$info['comment_count']|escape}" class="txt"/></td>
          <td class="vatop tips">{form_error('comment_count')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>{#share_count#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="share_count" value="{$info['share_count']|escape}" class="txt"/></td>
          <td class="vatop tips">{form_error('share_count')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>{#article_click#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="article_click" value="{$info['article_click']|escape}" class="txt"/></td>
          <td class="vatop tips">{form_error('article_click')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>审核人/审核时间</label></td>
        </tr>
        <tr>
          <td class="vatop rowform">{if $info['article_state'] == 3}<label>{$info['verify_username']|escape}/{$info['verify_time']|date_format:"%Y-%m-%d %H:%M:%S"}</label>{/if}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>发布人/发布时间</label></td>
        </tr>
        <tr>
          <td class="vatop rowform">{if $info['article_state'] == 3}<label>{$info['publish_username']|escape}/{$info['publish_time']|date_format:"%Y-%m-%d %H:%M:%S"}</label>{/if}</td>
          <td class="vatop tips"></td>
        </tr>
        
        {/if}
      </tbody>
    </table>
    
    <div class="fixedOpBar">
    	<input type="submit" name="tijiao" value="保存" class="msbtn"/>
    	{if $gobackUrl}
    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
    	{/if}
    </div>
   </form>
  <script type="text/javascript">
	function delImg(){
  		var src = $("#preview").find("img").attr('src');
  		
  		$.ajax({
  			url:"{admin_site_url($moduleClassName|cat:"/delimg")}?id={$info['id']}",
  			dataType: 'json',
  			data : { imgUrl : src },
  			success:function(jsonResp){
  				if(/成功/.test(jsonResp.message)){
  					$("#preview").html('');
  					document.getElementById("image_url").value = "";
  				}else{
  					showToast('error',jsonResp.message);
  				}
  			}
  		});
  		document.getElementById(preview);
  	}

	KindEditor.ready(function(K) {
		var uploadbutton = K.uploadbutton({
			button : K('#uploadButton')[0],
			fieldName : 'Filedata',
			extraParams : { formhash : formhash },
			url : '{admin_site_url("common/pic_upload")}?mod=cms_article',
			afterUpload : function(data) {
				refreshFormHash(data);
				if (data.error === 0) {
					K('#image_url').val(data.url);
					K('#preview').html('<img width="{$imageConfig['m']['width']}" height="{$imageConfig['m']['height']}" src="' + data.url + '"/><div><a href="javascript:delImg(this);">刪除</a></div>' );
					
				} else {
					alert(data.msg);
				}
			},
			afterError : function(str) {
				alert('自定义错误信息: ' + str);
			}
		});
		{if $redirectUrl}
		setTimeout(function(){
			location.href="{$redirectUrl}";
		},2000);
		{/if}
		
		uploadbutton.fileBox.change(function(e) {
			uploadbutton.submit();
		});
	});
 </script>
{include file="common/main_footer.tpl"}