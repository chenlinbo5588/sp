{include file="common/main_header.tpl"}
{config_load file="article.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('article/index')}"><span>管理</span></a></li>
      	<li><a {if empty($info['article_id'])}class="current"{/if} href="{admin_site_url('article/add')}"><span>新增</span></a></li>
      	{if $info['article_id']}<li><a class="current"><span>编辑</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <style type="text/css">
  
  .filelist span {
  	padding: 3px;
  }
  </style>
  <div class="feedback">{$feedback}</div>
  {if $info['article_id']}
  {form_open_multipart(admin_site_url('article/edit'),'id="article_form"')}
  {else}
  {form_open_multipart(admin_site_url('article/add'),'id="article_form"')}
  {/if}
  	<input type="hidden" name="article_id" value="{$info['article_id']}"/>
  	<table class="table tb-type2 nobdb">
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
	          <option {if $info['ac_id'] == $item['ac_id']}selected{/if} value="{$item['ac_id']}">{str_repeat('......',$item['level'])}{$item['ac_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('ac_id')} 文章的分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="articleForm">链接:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['article_url']}" name="article_url" id="article_url" class="txt"></td>
          <td class="vatop tips">{form_error('article_url')} 当填写&quot;链接&quot;后点击文章标题将直接跳转至链接地址，不显示文章内容。链接格式请以http://开头</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>显示:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="article_show1" {if $info['article_show']}class="cb-enable selected"{else}class="cb-enable"{/if} ><span>是</span></label>
            <label for="article_show0" {if $info['article_show']}class="cb-disable"{else}class="cb-disable selected"{/if} ><span>否</span></label>
            <input id="article_show1" name="article_show" {if $info['article_show']}checked="checked"{/if} value="1" type="radio">
            <input id="article_show0" name="article_show" {if $info['article_show'] != 1}checked="checked"{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('article_show')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['article_sort']}" name="article_sort" id="article_sort" class="txt"></td>
          <td class="vatop tips">{form_error('article_sort')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>文章封面(用于在列表页显示，如果不传则使用默认封面):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="hidden" name="article_pic_id" value="{$info['article_pic_id']}"/>
            <span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div id="previewArticlePic" class="type-file-preview">{if !empty($info['article_pic'])}<img src="{resource_url($info['article_pic'])}">{else}<img src="{resource_url('img/default.jpg')}">{/if}</div>
            </span>
            <span class="type-file-box">
              <input type='text' name='article_pic' value="{$info['article_pic']}" id='article_pic' class='type-file-text' />
              <input type='button' name='button' id='button' value='' class='type-file-button' />
              <input name="_pic" type="file" class="type-file-file" id="_pic" size="30" hidefocus="true" />
            </span>
            </td>
          <td class="vatop tips">支持格式jpg</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">文章内容:</label>{form_error('article_content')}</td>
        </tr>
        <tr>
        	<td colspan="2" ><textarea id="article_content" name="article_content" style="width:100%;height:480px;visibility:hidden;">{$info['article_content']}</textarea></td>
        	{include file="common/ke.tpl"}
			<script type="text/javascript">
	            var editor1;
	            KindEditor.ready(function(K) {
	                editor1 = K.create('textarea[name="article_content"]', {
	                    uploadJson : '{admin_site_url("common/pic_upload")}?mod=article',
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
        <tr>
          <td colspan="2" class="required"><label>文章作者:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" name="article_author" value="{$info['article_author']|escape}"/></td>
          <td class="vatop tips">{form_error('article_author')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>文章摘要(不填默认自动摘录正文内容):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="article_digest" style="width:300px;height:80px;">{$info['article_digest']|escape}</textarea></td>
          <td class="vatop tips">{form_error('article_digest')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">附件(PDF,WORD,JPG 格式):</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" id="divComUploadContainer"><input type="file" multiple="multiple" id="fileupload" name="fileupload" /></td>
        </tr>
        <tr>
       		<td colspan="2">
       			<ul id="thumbnails" class="filelist">
       			{foreach from=$fileList item=item}
       			<li id="{$item['id']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['id']}" /><span>{$item['orig_name']|escape}</span><span><a href="javascript:del_file_upload('{$item['id']}');">删除</a></span></p></li>
       			{/foreach}
       			</ul>
       		</td>
       	</tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
   </form>
  {include file="common/ajaxfileupload.tpl"}
  {include file="common/fileupload.tpl"}
  <script type="text/javascript">
	function del_file_upload(file_id)
	{
	    if(!window.confirm('您确定要删除吗?')){
	        return;
	    }
	    
	    $.getJSON('{admin_site_url("article/delfile")}?mod=article&file_id=' + file_id + "&article_id=" + $("input[name=article_id]").val(), function(result){
	    	refreshFormHash(result.data);
	        if(result){
	            $('#' + file_id).remove();
	        }else{
	            alert('删除失败');
	        }
	    });
	}
	
	$(function(){
	
		// 附件上传
	    $('#fileupload').each(function(){
	        $(this).fileupload({
	            dataType: 'json',
	            url: '{admin_site_url("article/addfile")}?mod=article',
	            done: function (e,data) {
	            	refreshFormHash(data.result);
	            	
	                if(data.result.error == 0){
	                	add_uploadedfile(data.result);
	                }
	            }
	        });
	    });
	    
	    function add_uploadedfile(file_data)
		{
		    var newImg = '<li id="' + file_data.id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.id + '" /><span>' +  file_data.orig_name + '</span><span><a href="javascript:del_file_upload(\'' + file_data.id + '\');">删除</a></span></li>';
		    $('#thumbnails').prepend(newImg);
		}
		
		$('input[class="type-file-file"]').change(uploadChange);
	    function uploadChange(){
	        var filepatd=$(this).val();
	        var extStart=filepatd.lastIndexOf(".");
	        var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();     
	        if(ext!=".JPG"&&ext!=".JPEG"){
	            alert("file type error");
	            $(this).attr('value','');
	            return false;
	        }
	        if ($(this).val() == '') return false;
	        ajaxFileUpload();
	    }
	    
	    function ajaxFileUpload()
	    {
	        $.ajaxFileUpload
	        (
	            {
	                url:'{admin_site_url("common/pic_upload")}?mod=article',
	                secureuri:false,
	                fileElementId:'_pic',
	                dataType: 'json',
	                data: { formhash : formhash , id : $("input[name=article_pic_id]").val() },
	                success: function (resp, status)
	                {
	                	refreshFormHash(resp);
	                	
	                    if (resp.error == 0){
	                        $("input[name=article_pic_id]").val(resp.id);
	                        $("input[name=article_pic]").val(resp.url);
	                        $("#previewArticlePic img").attr("src",resp.url);
	                    }else
	                    {
	                        alert(resp.msg);
	                    }
	                    $('input[class="type-file-file"]').bind('change',uploadChange);
	                },
	                error: function (resp, status, e)
	                {
	                    alert('upload failed');
	                    $('input[class="type-file-file"]').bind('change',uploadChange);
	                }
	            }
	        )
	    }
	})
 </script>
{include file="common/main_footer.tpl"}