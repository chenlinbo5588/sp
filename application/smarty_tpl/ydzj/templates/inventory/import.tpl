        <div class="w-tixing clearfix"><b>温馨提醒：</b>
	        <p>1、上传文件格式支持xls和xlsx，大小不超过2MB。</p>
	        <p>2、系统会按照预定模板扫描您的文件，并导入数据。<a href="{resource_url('example/hp_record.xls')}" target="_blank">下载模版</a></p>
	        <p>3、每次最多可以导入1000条解析记录，超出的部分将不会导入。</p>
	        <p>4、如果原来库存已经存在,重新导入将进行重写覆盖操作， </p>
	      </div>
        <div class="basic-information-list">
          {$stepHTML}
          <div class="hp-import-box">
            {form_open_multipart(site_url('inventory/import'),"id='inventoryForm'")}
            <input id="file_upload" type="file" name="Filedata"/>
            <input type="submit" class="master_btn" name="tijiao" value="开始导入"/>
            </form>
          </div>
          
        </div>
        
