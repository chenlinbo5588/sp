{include file="common/my_header.tpl"}
	{config_load file="lab_goods.conf"}
	<div class="w-tixing clearfix"><b>温馨提醒：</b>
	    <p>1、上传文件格式支持xls和xlsx，大小不超过2MB。</p>
        <p>2、系统会按照预定模板扫描您的文件，并导入数据。<a href="{resource_url('example/import_templte.xls')}" target="_blank">下载模版</a></p>
        <p>3、每次最多可以导入1000条解析记录，超出的部分将不会导入。</p>
        <p>4、累加模式将EXCEL表格中库存累加到现有库存上,覆盖模将用EXCEL表中的库存值覆盖数据库中的库存值， </p>
      </div>
	<form name="categoryForm" method="post" action="{site_url($uri_string)}" target="tagetframe">
		<table class="fulltable">
	      <tbody>
	        <tr class="noborder">
	          <td class="required w120"><label class="validation">导入文件:</label></td>
	          <td class="vatop rowform">
	              <div class="upload"><input type="button" id="uploadButton" value="选择Excel文件" /></div>
	          </td>
	        </tr>
	        <tr class="noborder">
	          <td class="required"><label class="validation">模式:</label></td>
	          <td class="vatop rowform">
	          	  <select name="import_mode">
	                <option value="覆盖模式">覆盖模式</option>
	                <option value="累加模式">累加模式</option>
	              </select>
	          </td>
	        </tr>
	         <tr>
               <td></td>
              <td><input type="submit" id="begin_import" name="submit" value="开始导入" class="master_btn grayed" disabled/></td>
            </tr>
	       </tbody>
    	</table>
      </form>
      <div class="rounded_box" id="process_area"><iframe name="tagetframe" width="100%" height="350"  frameborder="no" border="0" marginwidth="0" marginheight="0"></iframe></div>
      <div id="dialog-confirm" title="上传EXCEL文件" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span><span>请先上传文件,<span class="hightlight">3秒后自动关闭</span></p></div>
      {include file="common/jquery_ui.tpl"}
	  {include file="common/ke.tpl"}
		<script>
		    var editor1;
            KindEditor.ready(function(K) {
                var uploadbutton = K.uploadbutton({
                        button : K('#uploadButton')[0],
                        fieldName : 'Filedata',
                        url : '{site_url('lab_goods/upload')}',
                        afterUpload : function(data) {
                            var msg = data.message;
                            if(/成功/.test(msg)){
                                $("#begin_import").removeClass("grayed").attr('disabled',false);
                                
                            }else{
                                alert(data.message);
                            }
                        }
                });
                uploadbutton.fileBox.change(function(e) {
                	uploadbutton.submit();
                });

            });
		</script>
{include file="common/my_footer.tpl"}