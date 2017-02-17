{form_open_multipart(admin_site_url('building/edit?id='|cat:$id),'id="user_form"')}
<ul class="edit_ul col3 clearfix">
    {foreach from=$fields key=key item=item}
    <li>
      <label class="side_lb{if $item['required']} required{/if}" for="{$key}">{if $item['required']}<em class="required">*</em>{/if}{$item['title']|escape}{$item['tip']}:</label>
        {if $item['type'] == 'select'}
        <select name="{$key}" id="{$key}">
        {foreach from=$item['dataSource'] key=dk item=dv}
        <option value="{$dk}" {if $info['attributes'][$key] == $dk}selected{/if}>{$dv}</option>
        {/foreach}
        </select>
        {elseif $item['type'] == 'textarea'}
        <textarea name="{$key}" id="{$key}">{$info['attributes'][$key]|escape}</textarea>
        {else}
        <input type="text" value="{$info['attributes'][$key]|escape}" name="{$key}" id="{$key}" class="txt" {if $item['readonly']}readonly{/if}>
        {/if}
    </li>
    {/foreach}
    <li>
        <div class="row" style="position:relative;">
		    <label class="side_lb" style="float:left" for="_pic">上传图片：</label>
		    <input type="file" class="type-file-file" style="width:80%;" id="_pic" name="_pic" value=""  hidefocus="true"/>
		    <input type='text' name='pic' style="width:80px;" id='pic' class='type-file-text' />
		    <input type='button' name='button' id='button' value='' class='type-file-button' />
		</div>
		<div id="wait_loading" style="text-align:center;display:none;">
		    <div><img class="nature" src="{resource_url('img/loading/loading.gif')}"/></div>
		    <div>正在上传,请稍等..</div>
		</div>
    </li>
 </ul>
 <div class="clearfix">
 	<div class="fr"><input type="button" name="submit" id="save" value="保存" class="msbtn"/></div>
 </div>

{if $photos}
<div id="photos">
    {foreach from=$photos item=item}
    <a class="fancybox" data-fancybox-group="gallery" href="{str_replace('IMAGE_255x255','all',$item)}"><img src="{$item}" /></a>
    {/foreach}
</div>
<div style="margin:auto;text-align:center"><div id="photonav"></div></div>
{/if}
 </form>
 <script>
 
 var objectid = "{$id}";
 $(function(){
    
    $('#save').click(function(){
        var d=$('#user_form').serialize();
        
        $.ajax({
        	'type':'POST',
        	'url':$("#user_form").attr("action"),
        	data: d,
        	dataType:'json',
        	success:function(resp){
	            if(resp.message == '保存成功'){
	                showToast('success',resp.message);
	            }else{
	            	showToast('error',resp.message);
	            }
        	},
        	error:function(){
        		showToast('error','错误');
        	}
        });
        
    });
    
    
    
    
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
        
        $("#wait_loading").show();
        $("#save").hide();
        ajaxFileUpload();
    }
    
    
    function ajaxFileUpload()
    {
        $.ajaxFileUpload
        (
            {
                url:'{admin_site_url("building/photo")}',
                secureuri:false,
                fileElementId:'_pic',
                dataType: 'json',
                data: { formhash : formhash , id : objectid , photos:"{$info['attributes']['photos']}"},
                success: function (resp, status)
                {
                    $("#wait_loading").hide();
                    $("#save").show();
                    
                    if (resp.status == 1){
                    	showToast('success',resp.msg);
                        $("input[name=pic]").val(resp.path);
                    }else
                    {
                        showToast('error',resp.msg);
                    }
                    $('input[class="type-file-file"]').bind('change',uploadChange);
                },
                error: function (resp, status, e)
                {
                    $("#wait_loading").hide();
                    showToast('error','上传失败');
                    $('input[class="type-file-file"]').bind('change',uploadChange);
                }
            }
        )
    }
    
	$('.fancybox').fancybox();
 });
 
 </script>
 