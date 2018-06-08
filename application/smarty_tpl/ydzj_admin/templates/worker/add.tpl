{include file="common/main_header.tpl"}
  {config_load file="worker.conf"}
  {include file="common/sub_nav.tpl"}
  {if $info['id']}
  {form_open_multipart(admin_site_url($moduleClassName|cat:'/edit?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open_multipart(admin_site_url($moduleClassName|cat:'/add'),'id="infoform"')}
  {/if}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="id_type">{#id_type#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="id_type" id="id_type">
	          <option value="" >请选择...</option>
	          {foreach from=$idTypeList item=item}
	          <option {if $info['id_type'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('id_type')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="id_no">{#id_no#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['id_no']|escape}" name="id_no" id="id_no" class="txt"></td>
          <td class="vatop tips">{form_error('id_no')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="age">{#sex#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="sex">
          		<option value="2" {if $info['sex'] == 2}selected{/if}>女</option>
          		<option value="1" {if $info['sex'] == 1}selected{/if}>男</option>
          	</select>
          </td>
          <td class="vatop tips">{form_error('sex')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="age">{#age#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['age']|escape}" name="age" id="age" class="txt"></td>
          <td class="vatop tips">{form_error('age')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="id_no">{#birthday#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" class="datepicker" value="{$info['birthday']|escape}" name="birthday" id="birthday" class="txt"></td>
          <td class="vatop tips">{form_error('birthday')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="jiguan">{#jiguan#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="jiguan" id="jiguan">
	          <option value="">请选择...</option>
	          {foreach from=$jiguanList item=item}
	          <option {if $info['jiguan'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('jiguan')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#mobile#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['mobile']|escape}" name="mobile" id="mobile" class="txt"></td>
          <td class="vatop tips">{form_error('mobile')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#address#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['address']|escape}" name="address" id="address" class="txt"></td>
          <td class="vatop tips">{form_error('address')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="id_no">{#degree#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="degree" id="degree">
	          <option value="">请选择...</option>
	          {foreach from=$xueliList item=item}
	          <option {if $info['degree'] == $item['id']}selected{/if} value="{$item['id']}">{$item['show_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('degree')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">人员照片: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="hidden" name="old_pic" value="{if $info['old_pic']}{$info['old_pic']}{/if}"/>
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if $info['avatar_m']}<img src="{resource_url($info['avatar_m'])}">{else if $info['avatar_b']}<img src="{resource_url($info['avatar_b'])}">{else}<img src="{resource_url($info['avatar'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='worker_pic_txt' value="{if $info['avatar']}{$info['avatar']}{/if}" id='worker_pic_txt' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="worker_pic" type="file" class="type-file-file" id="worker_pic" size="30" hidefocus="true" nc_type="change_brand_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">工作人员照片。</span></td>
        </tr>
       	<tr>
          <td colspan="2" class="required">其他证件图片上传(JPG格式):</td>
        </tr>
        <tr class="noborder">
          <td colspan="2">
          	<input type="file" multiple="multiple" id="fileupload" name="fileupload" />
          	<style>
   				.bar {
				    height: 18px;
				    background: green;
				}
   			</style>
   			<div id="progress" style="width:200px;margin:5px 0;">
			    <div class="bar" style="width: 0%;"></div>
			</div>
          </td>
        </tr>
        <tr>
       		<td colspan="2">
       			<ul id="thumbnails" class="thumblists">
       			{foreach from=$fileList item=item}
       			<li id="{$item['image_aid']}" class="picture"><input type="hidden" name="file_id[]" value="{$item['image_aid']}" /><div class="size-64x64"><span class="thumb"><i></i><img src="{resource_url($item['image_b'])}" alt="" width="64px" height="64px"/></span></div><p><span><a href="javascript:del_file_upload('{$item['image_aid']}');">删除</a></span></p></li>
       			{/foreach}
       			</ul>
       		</td>
       	</tr>
        <tr>
          <td colspan="2" class="required"><label>备注: </label>{form_error('remark')}</td>
        </tr>
        <tr>
        	<td colspan="2" ><textarea id="remark" name="remark" style="width:100%;height:300px;visibility:hidden;">{$info['remark']}</textarea></td>
        	{include file="common/ke.tpl"}
			<script type="text/javascript">
	            var editor1;
	            KindEditor.ready(function(K) {
	                editor1 = K.create('textarea[name="remark"]', {
	                    uploadJson : '{admin_site_url("common/pic_upload")}?mod={$moduleClassName}',
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
  	
  	var province_idcard = {$province_idcard};
  	
	function del_file_upload(file_id)
	{
	    if(!window.confirm('您确定要删除吗?')){
	        return;
	    }
	    
	    $.getJSON('{admin_site_url($moduleClassName|cat:"/delimg")}?mod={$moduleClassName}&file_id=' + file_id + "&id=" + $("input[name=id]").val(), function(result){
	    	refreshFormHash(result.data);
	    	
	        if(result){
	            $('#' + file_id).remove();
	        }else{
	            alert('删除失败');
	        }
	    });
	}
	
	$(function(){
	
		$( ".datepicker" ).datepicker();
		
		$("#worker_pic").change(function(){
			$("#worker_pic_txt").val($(this).val());
		});
		
		$("#id_no").bind("focusout",function(){
			var idNo = $.trim($(this).val());
			var idType = $("#id_type option:selected").html();
			if(('身份证' == idType || '驾驶证' == idType) && idNo.length >= 15){
				var sex = parseInt(idNo.substring(idNo.length - 2, idNo.length - 1));
				
				if(sex % 2 == 0){
					$("select[name=sex]").find("option:eq(0)").attr("selected","selected");
				}else{
					$("select[name=sex]").find("option:eq(1)").attr("selected","selected");
				}
				
				var birthday = idNo.substring(6,14);
				$("#birthday").val(birthday.substring(0,4) + '-' + birthday.substring(4,6) + '-' + birthday.substring(6,8));
				
				var currentYear = (new Date()).getFullYear();
				var age = currentYear - parseInt(birthday.substring(0,4));
				$("#age").val(age);
				
				if(typeof(province_idcard[idNo.substring(0,3) + "000"])){
					var provinceName = province_idcard[idNo.substring(0,3) + "000"];
					
					$("#jiguan option").each(function(){
						if(provinceName == $(this).html()){
							$(this).attr("selected","selected");
						}
					});
					
				}
			}
			
		});
		
		// 图片上传
	    $('#fileupload').each(function(){
	        $(this).fileupload({
	            dataType: 'json',
	            url: '{admin_site_url($moduleClassName|cat:"/addimg")}?mod={$moduleClassName}',
	            add: function (e, data) {
	            	$('#progress .bar').css('width',0);
					data.submit();
		        },
	            done: function (e,data) {
	            	refreshFormHash(data.result);
	                if(data.result.error == 0){
	                	add_uploadedfile(data.result);
	                }
	            },
	            progressall: function (e, data) {
			        var progress = parseInt(data.loaded / data.total * 100, 10);
					//console.log(progress);
			        $('#progress .bar').css(
			            'width',
			            progress + '%'
			        );
			    }
	        });
	    });
	    
	    function add_uploadedfile(file_data)
		{
		    var newImg = '<li id="' + file_data.id + '" class="picture"><input type="hidden" name="file_id[]" value="' + file_data.id + '" /><div class="size-64x64"><span class="thumb"><i></i><img src="' + file_data.url + '" alt="" width="64px" height="64px"/></span></div><p><span><a href="javascript:del_file_upload(\'' + file_data.id + '\');">删除</a></span></p></li>';
		    $('#thumbnails').prepend(newImg);
		}
	})
  </script>
 
{include file="common/main_footer.tpl"}