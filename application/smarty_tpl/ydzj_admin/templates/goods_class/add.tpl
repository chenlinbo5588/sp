{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品分类</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('goods_class/category')}"><span>管理</span></a></li>
      	<li><a {if empty($info['gc_id'])}class="current"{/if} href="{admin_site_url('goods_class/add')}"><span>新增</span></a></li>
        {if $info['gc_id']}<li><a class="current"><span>编辑</span></a></li>{/if}
      	<li><a href="{admin_site_url('goods_class/export')}"><span>导出</span></a></li>
      	<li><a href="{admin_site_url('goods_class/import')}"><span>导入</span></a></li>
      	<li><a href="{admin_site_url('goods_class/tag')}"><span>TAG管理</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  
  {if $info['gc_id']}
  {form_open(admin_site_url('goods_class/edit'),'id="goods_class_form"')}
  {else}
  {form_open(admin_site_url('goods_class/add'),'id="goods_class_form"')}
  {/if}
  	<input type="hidden" name="gc_id" value="{$info['gc_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name_cn">分类中文名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name_cn']|escape}" name="name_cn" id="name_cn" class="txt"></td>
          <td class="vatop tips">{form_error('name_cn')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name_en">分类英文名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name_en']|escape}" name="name_en" id="name_en" class="txt"></td>
          <td class="vatop tips">{form_error('name_en')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_parent_id">上级分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="gc_parent_id" id="category">
	          <option value="">请选择...</option>
	          {foreach from=$list item=item}
	          <option {if $info['gc_parent_id'] == $item['gc_id']}selected{/if} value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['gc_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('gc_parent_id')}如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>分类图片(用于在列表页显示，如果不传则使用默认封面):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="hidden" name="article_pic_id" value="{$info['gc_pic_id']}"/>
            <span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div id="previewPic" class="type-file-preview">{if !empty($info['gc_pic'])}<img src="{resource_url($info['gc_pic'])}">{else}<img src="{resource_url('img/default.jpg')}">{/if}</div>
            </span>
            <span class="type-file-box">
              <input type='text' name='gc_pic' value="{$info['gc_pic']}" id='article_pic' class='type-file-text' />
              <input type='button' name='button' id='button' value='' class='type-file-button' />
              <input name="_pic" type="file" class="type-file-file" id="_pic" size="30" hidefocus="true" />
            </span>
            </td>
          <td class="vatop tips">支持格式jpg</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['gc_sort']}" name="gc_sort" id="gc_sort" class="txt"></td>
          <td class="vatop tips">{form_error('gc_sort')} 数字范围为0~255，数字越小越靠前</td>
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
  <script type="text/javascript">
	$(function(){
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
	                url:'{admin_site_url("common/pic_upload")}?mod=goods_class',
	                secureuri:false,
	                fileElementId:'_pic',
	                dataType: 'json',
	                data: { formhash : formhash , id : $("input[name=gc_pic_id]").val() },
	                success: function (resp, status)
	                {
	                	refreshFormHash(resp);
	                	
	                    if (resp.error == 0){
	                        $("input[name=gc_pic_id]").val(resp.id);
	                        $("input[name=gc_pic]").val(resp.url);
	                        $("#previewPic img").attr("src",resp.url);
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