{include file="common/main_header_navs.tpl"}
  {config_load file="article.conf"}
  {if $info['id']}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="infoform"')}
  <input type="hidden" name="recommend_id" value="{$recommendinfo['id']}"/>
  {/if}
</body>
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="table tb-type2">
      <tbody>
      <tr class="noborder">
          <td colspan="2" class="required">文章序号</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['cms_id']|escape}" name="cms_id" id="cms_id" class="txt"></td>
           <td class="vatop tips">输入你所要添加文章的序号，若没有文章。输入正确的序号之后自动补全 关联信息</td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="title">{#title#}标题</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['title']|escape}" name="title" class="txt"></td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="url">{#url#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['url']|escape}" name="url" id="url" class="txt"></td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="img_url">图片路径</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            
            <div class="upload"><input type='text' readonly class="txt" name='img_url' id='img_url' value="{$info['img_url']}"/><input type="button" id="uploadButton" value="浏览" /></div>
            </td>
          <td class="vatop tips">{form_error('img_url')} jpg格式 </td>
	    </tr>
	    <tr class="noborder">
	    	<td colspan="2"><div id="preview">{if $info['img_url']}<img src="{resource_url($info['img_url'])}" width="360" height="200"/><div><a href="javascript:delImg(this);">刪除</a></div>{/if}</div>
	    	</td>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="synopsis">{#synopsis#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['synopsis']|escape}" name="synopsis" id="synopsis" class="txt"></td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="release_time">{#release_time#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{date('Y-m-d h:i:s',$info['release_time'])|escape}" name="release_time" id="release_time" class="txt"></td>
        </tr>

        
 	 	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="display">{#display#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['display']|escape}" name="display" id="display" class="txt"></td>
        </tr>	
        
 	 	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="startdate">{#startdate#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{date('Y-m-d h:i:s',$info['startdate'])|escape}" name="startdate" id="startdate" class="datepicker"></td>
        </tr>
        
 	 	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="enddate">{#enddate#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input data-field="datetime" type="text" value="{date('Y-m-d h:i:s',$info['enddate'])|escape}" name="enddate" id="enddate" class="datepicker"></td>
        </tr>
        
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="submit" name="tijiao" value="保存" class="msbtn"/>
          	{if $gobackUrl}
	    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
	    	{/if}
          </td>
        </tr>
      </tbody>
    </table>
	
	{include file="common/ke.tpl"}
  </form>
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")],searchTitle = "{admin_site_url('recommend/gettitle')}";
		
		function delImg(){
  		var src = $("#preview").find("img").attr('src');
  		
  		$.ajax({
  			url:"{admin_site_url($moduleClassName|cat:"/delimg")}?id={$info['id']}",
  			dataType: 'json',
  			data : { imgUrl : src },
  			success:function(jsonResp){
  				if(/成功/.test(jsonResp.message)){
  					$("#preview").html('');
  					document.getElementById("img_url").value = "";
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
			url : '{admin_site_url("common/pic_upload")}?mod=recommend',
			afterUpload : function(data) {
				refreshFormHash(data);
				if (data.error === 0) {
					K('#img_url').val(data.url);
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
	

	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		$(".datepicker").datepicker();
		bindAjaxSubmit("#infoform");
		
			$( "#cms_id" ).autocomplete({
			source: searchTitle,
			minLength: 2,
			select: function( event, ui ) {
				$("input[name=title]").val(ui.item.title);
				$("input[name=url]").val(ui.item.jump_url);
				$("input[name=img_url]").val(ui.item.img_url);
				$("input[name=synopsis]").val(ui.item.synopsis);
				$("input[name=release_time]").val(ui.item.release_time);
			}
	    });

	});
  </script>

{include file="common/main_footer.tpl"}