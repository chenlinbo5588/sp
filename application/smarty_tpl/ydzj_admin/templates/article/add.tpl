{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>品牌</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('article/index')}"><span>管理</span></a></li>
      	<li><a class="current"><span>{if $info['article_id']}编辑{else}新增{/if}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
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
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
   </form>
  <script type="text/javascript">
	$(function(){
		$("#goods_pic").change(function(){
			$("#goods_pic_txt").val($(this).val());
		});
	})
 </script>
{include file="common/main_footer.tpl"}