{include file="common/main_header.tpl"}
  {form_open(admin_site_url('message/email_tpl_edit'),'name="form1" id="form_templates" onsubmit="return validation(this);"')}
    <input type="hidden" name="code" value="{$info['code']}" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>正在编辑: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">{$info['name']}</td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">{if $info['type'] == 0}邮件{else}短消息{/if}标题:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['title']}" name="title" class="txt"></td>
          <td class="vatop tips">短消息标题没实际作用{form_error('title')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="link">邮件正文:</label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">{form_error('content')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2">
          	<textarea id="content" name="content" style="width:700px;height:300px;visibility:hidden;">{$info['content']}</textarea>
			{include file="common/ke.tpl"}
			<script type="text/javascript">
	            var editor1;
				
	            KindEditor.ready(function(K) {
	                editor1 = K.create('textarea[name="content"]', {
	                    uploadJson : '{admin_site_url("common/pic_upload")}',
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
	            
	            function validation(form){
	                if($.trim(form['title'].value) == ''){
	                    alert("请输入标题");
	                    return false;
	                }
	                
	                if(editor1.isEmpty() == true){
	                    alert("请输入内容");
	                    return false;
	                }
	                
	                return true;            
	            }
	        </script>
			
			</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" name="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>

{include file="common/main_footer.tpl"}