{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('goods/index')}"><span>管理</span></a></li>
      	<li><a {if empty($info['goods_id'])}class="current"{/if} href="{admin_site_url('goods/add')}"><span>新增</span></a></li>
        {if $info['goods_id']}<li><a class="current"  href="{admin_site_url('goods/edit/')}?goods_id={$info['goods_id']}" ><span>编辑</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['goods_id']}
  {form_open_multipart(admin_site_url('goods/edit?goods_id='|cat:$info['goods_id']),'id="goods_form"')}
  {else}
  {form_open_multipart(admin_site_url('goods/add'),'id="goods_form"')}
  {/if}
  	<input type="hidden" name="goods_id" value="{$info['goods_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="goods_name">商品中文名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['goods_name']|escape}" name="goods_name" id="goods_name" class="txt"></td>
          <td class="vatop tips">{form_error('goods_name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="goods_name_en">商品英文名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['goods_name_en']|escape}" name="goods_name_en" id="goods_name_en" class="txt"></td>
          <td class="vatop tips">{form_error('goods_name_en')}</td>
        </tr>
        {if $info['goods_id']}
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="goods_name">商品代码:{$info['goods_code']}</label></td>
        </tr>
        {/if}
        <tr>
          <td colspan="2" class="required"><label for="brandId">品牌:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="brand_id" id="brandId">
	          <option value="">请选择...</option>
	          {foreach from=$brandList item=item}
	          <option {if $info['brand_id'] == $item['brand_id']}selected{/if} value="{$item['brand_id']}">{$item['brand_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('brand_id')} 商品的品牌</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="goodsClassId">商品分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="gc_id" id="goodsClassId">
	          <option value="">请选择...</option>
	          {foreach from=$goodsClassList item=item}
	          <option {if $info['gc_id'] == $item['gc_id']}selected{/if} value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['name_cn']}({$item['name_en']})</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('class_id')} 商品的分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required">商品主图: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="hidden" name="old_pic" value="{if $info['goods_pic']}{$info['goods_pic']}{/if}"/>
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if $info['goods_pic_m']}<img src="{resource_url($info['goods_pic_m'])}">{else if $info['goods_pic_b']}<img src="{resource_url($info['goods_pic_b'])}">{else}<img src="{resource_url($info['goods_pic'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='goods_pic_txt' value="{if $info['goods_pic']}{$info['goods_pic']}{/if}" id='goods_pic_txt' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="goods_pic" type="file" class="type-file-file" id="goods_pic" size="30" hidefocus="true" nc_type="change_brand_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">上传商品默认主图，如多规格值时将默认使用该图或分规格上传各规格主图；支持jpg，建议使用尺寸800x800像素以上、大小不超过1M的正方形图片，上传后的图片将会自动保存在图片空间的默认分类中。</span></td>
        </tr>
       	<tr>
          <td colspan="2" class="required">商品图片上传(JPG格式 , 用于显示在详情页面幻灯片展示):</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" id="divComUploadContainer"><input type="file" multiple="multiple" id="fileupload" name="fileupload" /></td>
        </tr>
        <tr>
       		<td colspan="2">
       			<ul id="thumbnails" class="thumblists">
       			{foreach from=$fileList item=item}
       			<li id="{$item['goods_image_aid']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['goods_image_aid']}" /><div class="size-64x64"><span class="thumb"><i></i><img src="{resource_url($item['goods_image_b'])}" alt="" width="64px" height="64px"/></span></div><p><span><a href="javascript:insert_editor('{resource_url($item['goods_image_b'])}');">插入</a></span><span><a href="javascript:del_file_upload('{$item['goods_image_aid']}');">删除</a></span></p></li>
       			{/foreach}
       			</ul>
       		</td>
       	</tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">商品中文版描述: </label>{form_error('goods_intro')}</td>
        </tr>
        <tr>
        	<td colspan="2" ><textarea id="goods_intro" name="goods_intro" style="width:100%;height:480px;visibility:hidden;">{$info['goods_intro']}</textarea></td>
        	{include file="common/ke.tpl"}
			<script type="text/javascript">
	            var editor1;
				
	            KindEditor.ready(function(K) {
	                editor1 = K.create('textarea[name="goods_intro"]', {
	                    uploadJson : '{admin_site_url("common/pic_upload")}?mod=goods',
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
        <tr>
          <td colspan="2"><label">商品英文版描述: </label>{form_error('goods_intro_en')}</td>
        </tr>
        <tr>
        	<td colspan="2" ><textarea id="goods_intro_en" name="goods_intro_en" style="width:100%;height:480px;visibility:hidden;">{$info['goods_intro_en']}</textarea></td>
        	{include file="common/ke.tpl"}
			<script type="text/javascript">
	            var editor2;
				
	            KindEditor.ready(function(K) {
	                editor2 = K.create('textarea[name="goods_intro_en"]', {
	                    uploadJson : '{admin_site_url("common/pic_upload")}?mod=goods',
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
        <tr>
          <td class="vatop rowform onoff"><label for="goods_commend1" {if $info['goods_commend']}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="goods_commend0" {if $info['goods_commend']}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="goods_commend1" name="goods_commend" {if $info['goods_commend']}checked{/if} value="1" type="radio">
            <input id="goods_commend0" name="goods_commend" {if $info['goods_commend'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">选择被推荐的图片将在所有商品列表页“推荐商品”位置展现。</td>
        </tr>
        <tr>
          <td colspan="2" class="required">审核通过: </td>
        </tr>
        <tr>
          <td class="vatop rowform onoff"><label for="goods_verify1" {if $info['goods_verify']}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="goods_verify0" {if $info['goods_verify']}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="goods_verify1" name="goods_verify" {if $info['goods_verify']}checked{/if} value="1" type="radio">
            <input id="goods_verify0" name="goods_verify" {if $info['goods_verify'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">选择被推荐的图片将在所有商品列表页“推荐商品”位置展现。</td>
        </tr>
        <tr>
          <td colspan="2" class="required">是否发布: </td>
        </tr>
        <tr>
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
  {include file="common/fileupload.tpl"}
  <script type="text/javascript">
  	function insert_editor(file_path){
		editor1.insertHtml('<img src="'+ file_path + '" alt="'+ file_path + '"/>');
	}
	
	function del_file_upload(file_id)
	{
	    if(!window.confirm('您确定要删除吗?')){
	        return;
	    }
	    
	    $.getJSON('{admin_site_url("goods/delimg")}?mod=goods&file_id=' + file_id + "&goods_id=" + $("input[name=goods_id]").val(), function(result){
	    	refreshFormHash(result.data);
	    	
	        if(result){
	            $('#' + file_id).remove();
	        }else{
	            alert('删除失败');
	        }
	    });
	}
	
	$(function(){
		$("#goods_pic").change(function(){
			$("#goods_pic_txt").val($(this).val());
		});
		
		// 图片上传
	    $('#fileupload').each(function(){
	        $(this).fileupload({
	            dataType: 'json',
	            url: '{admin_site_url("goods/addimg")}?mod=goods',
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
		    var newImg = '<li id="' + file_data.id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.id + '" /><div class="size-64x64"><span class="thumb"><i></i><img src="' + file_data.url + '" alt="" width="64px" height="64px"/></span></div><p><span><a href="javascript:insert_editor(\'' + file_data.url + '\');">插入</a></span><span><a href="javascript:del_file_upload(\'' + file_data.id + '\');">删除</a></span></p></li>';
		    $('#thumbnails').prepend(newImg);
		}
	})
  </script>
 
{include file="common/main_footer.tpl"}