{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>品牌</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('goods/index')}"><span>管理</span></a></li>
      	<li><a class="current"><span>{if $info['article_id']}编辑{else}新增{/if}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['article_id']}
  {form_open_multipart(admin_site_url('article/edit'),'id="goods_form"')}
  {else}
  {form_open_multipart(admin_site_url('article/add'),'id="goods_form"')}
  {/if}
  	<input type="hidden" name="article_id" value="{$info['article_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="article_title">商品名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['article_title']|escape}" name="article_title" id="article_title" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('article_title')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="brandId">品牌:</label></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="goodsClassId">所属分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="ac_id" id="articleClassId">
	          <option value="">请选择...</option>
	          {foreach from=$articleClassList item=item}
	          <option {if $info['ac_id'] == $item['ac_id']}selected{/if} value="{$item['ac_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['ac_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('ac_id')} 商品的分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required">商品主图: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="hidden" name="old_pic" value="{if $info['goods_pic']}{$info['goods_pic']}{/if}"/>
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if !empty($info['goods_pic'])}<img src="{resource_url($info['goods_pic'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='goods_pic_txt' value="{if $info['goods_pic']}{$info['goods_pic']}{/if}" id='goods_pic_txt' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="goods_pic" type="file" class="type-file-file" id="goods_pic" size="30" hidefocus="true" nc_type="change_brand_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">上传商品默认主图，如多规格值时将默认使用该图或分规格上传各规格主图；支持jpg、gif、png格式上传或从图片空间中选择，建议使用尺寸800x800像素以上、大小不超过1M的正方形图片，上传后的图片将会自动保存在图片空间的默认分类中。</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required">文字内容: {form_error('article_content')}</td>
        </tr>
        <tr>
        	<td colspan="2" ><textarea id="goods_intro" name="goods_intro" style="width:100%;height:480px;visibility:hidden;">{$info['article_content']}</textarea></td>
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
          <td colspan="2" class="required">是否推荐: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="goods_recommend1" {if $info['goods_recommend']}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="goods_recommend0" {if $info['goods_recommend']}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="goods_recommend1" name="goods_recommend" {if $info['goods_recommend']}checked{/if} value="1" type="radio">
            <input id="goods_recommend0" name="goods_recommend" {if $info['goods_recommend'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">选择被推荐的图片将在所有商品列表页“推荐商品”位置展现。</td>
        </tr>
        <tr>
          <td colspan="2" class="required">审核通过: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="goods_verify1" {if $info['goods_verify']}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="goods_verify0" {if $info['goods_verify']}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="goods_verify1" name="goods_verify" {if $info['goods_verify']}checked{/if} value="1" type="radio">
            <input id="goods_verify0" name="goods_verify" {if $info['goods_verify'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">选择被推荐的图片将在所有商品列表页“推荐商品”位置展现。</td>
        </tr>
        <tr>
          <td colspan="2" class="required">是否发布: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="goods_state1" {if $info['goods_state']}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="goods_state0" {if $info['goods_state']}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="goods_state1" name="goods_state" {if $info['goods_state']}checked{/if} value="1" type="radio">
            <input id="goods_state0" name="goods_state" {if $info['goods_state'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">是否发布。</td>
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