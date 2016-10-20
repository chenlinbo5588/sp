        <div class="basic-information-list">
          {$stepHTML}
          <div class="hp-import-box">
            {form_open(site_url('inventory/import'),"id='inventoryForm' target='tagetframe'")}
            <input id="file_upload" type="file" name="Filedata"/>
            <input type="submit" class="master_btn disabled" name="tijiao" value="开始导入" style="display:none;"/>
          </div>
          <div id="process_area" style="display:none;"><iframe name="tagetframe" width="100%" height="80"  frameborder="no" border="0" marginwidth="0" marginheight="0"></iframe></div>
          <div class="w-tixing clearfix"><b>温馨提醒：</b>
            <p>1、上传文件格式支持xls和xlsx，大小不超过2MB。</p>
            <p>2、系统会按照预定模板扫描您的文件，并导入数据。<a href="{resource_url('example/hp_record.xls')}" target="_blank">下载模版</a></p>
            <p>3、每次最多可以导入1000条解析记录，超出的部分将不会导入。</p>
            <p>4、如果原来库存已经存在重新导入将进行更新覆盖操作， </p>
          </div>
        </div>
        {include file="common/uploadify.tpl"}
        <script type="text/javascript">
        
        $('#file_upload').uploadify({
            'fileTypeDesc' : 'Excel文件',
            'buttonText' : '选择Excel文件',
            'fileTypeExts' : '*.xls;*.xlsx',
            'formData'     : {
                
            },
            'swf'      : "{resource_url('js/uploadify/uploadify.swf')}",
            'uploader' : "{site_url('inventory/upload')}",
            'onUploadSuccess' : function(file, data, response) {
                console.log(data);
                var json = $.parseJSON(data);
                if(/成功/.test(json.message)){
                	$(".w-step2:eq(0)").addClass("w-step-past").removeClass("w-step-cur");
                	$(".w-step2:eq(1)").addClass("w-step-past-cur").removeClass("w-step-cur-future");
                    $("input[name=tijiao]").show();
                    $("#process_area").show();
                }
            }
        });
        
        
        </script>
