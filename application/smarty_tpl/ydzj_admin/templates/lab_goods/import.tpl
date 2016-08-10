{include file="common/lab_admin_header.tpl"}
    <div class="submenu">
        <div class="clear"></div>
    </div>
    <div class="center_content"> 
	    <div id="right_wrap">
		    <div id="right_content">
		      
		      <form name="categoryForm" method="post" action="{base_url('lab_goods/import_goods')}" target="tagetframe" onsubmit="return validation(this);">
		       <div class="form">
	                <div class="form_row"><span class="tip">累加模式将EXCEL表格中库存累加到现有库存上,覆盖模将用EXCEL表中的库存值覆盖数据库中的库存值</span><span class="warning"> 文件最大6M</span></div>
		            <div class="form_row">
                      <label class="require"><em>*</em>导入文件:</label>
                      <input type="hidden" name="file_id" id="file_id" value=""/>
                      <input type="text" name="goods_file" id="goods_file" style="width:200px;" value="" /><input type="button" name="upload_btn" id="upload_btn" value="选择文件..." />
                      
                    </div>
                    <div class="form_row">
                      <label class="require"><em>*</em>模式:</label>
                      <select class="form_select" name="import_mode">
                        <option value="覆盖模式">覆盖模式</option>
                        <option value="累加模式">累加模式</option>
                      </select>
                    </div>
                    
                    <div class="form_row paddingleft100">
	                   <input type="submit" class="form_submit" id="begin_import" value="开始导入" />
	                   {if $gobackUrl }<input type="hidden" name="gobackUrl" value="{$gobackUrl}"/><input type="button" class="form_submit" value="返回" onclick="location.href='{$gobackUrl}'" />{/if}
	               </div>
		        </div>
		        </form>
		        <div class="rounded_box" id="process_area" style="display:none;"><iframe name="tagetframe" width="100%" height="350"  frameborder="no" border="0" marginwidth="0" marginheight="0"></iframe></div>
		        <div class="rounded_box">
                  <div class="excel"><a  href="{base_url('images/lab/import_templte.xls')}">Excel导入模版下载</a></div>
                  <div class="tip">【Excel 数据示例如下】</div>
                  <img src="{base_url('images/lab/excel_template.png')}"/>
              </div>
		    </div><!-- end of right content-->
		</div><!-- end of right wrap -->
		
		{include file="common/ke.tpl"}
		<script>
		
		    var editor1;
            KindEditor.ready(function(K) {
                var uploadbutton = K.uploadbutton({
                        button : K('#upload_btn')[0],
                        fieldName : 'imgFile',
                        url : '{base_url('attachment/upload/?expire_time=86400')}',
                        afterUpload : function(data) {
                            if (data.error === 0) {
                                 $("#file_id").val(data.id);
                                 $("#goods_file").val(data.title);
                            } else {
                                $.jBox.error(data.message, '提示');
                            }
                        }
                });
                uploadbutton.fileBox.change(function(e) {
                        uploadbutton.submit();
                });

            });
            
            function validation(form){
                if(form['file_id'].value == ''){
                    $.jBox.error("请先上传文件", '提示');
                    return false;
                }
               
                $("#process_area").fadeIn();
                $("#begin_import").prop("disabled",true);
                
                return true;
            }
            
		</script>
		{include file="./side.tpl"} 
{include file="common/lab_admin_footer.tpl"}