{include file="common/my_header.tpl"}
    {config_load file="person.conf"}
    <div class="w-tixing clearfix"><b>温馨提醒：</b>
	    <p>1. 可通过导入完成权力人的信息添加或更新,系统会按照预定模板扫描您的文件，一次最多{config_item('import_per_limit')}条,超出的部分将不会导入。<i class="excel"></i><a href="{resource_url('example/person.xls')}">点击下载模版</a></p>
	    <p>2. Excel模版中证件号码必填，系统根据证件号码识别权力人主体，如果系统中已经存在该证件号码，则将根据您EXCEL表格中提供的信息进行更新。</p>
	</div>
    <div class="basic-information-list">
      {$stepHTML}
      <div class="hp-import-box">
        {form_open_multipart(site_url('person/import'),"id='personImportForm'")}
        <input id="file_upload" type="file" name="Filedata"/>
        <input type="submit" class="master_btn" name="tijiao" value="开始导入"/>
        </form>
      </div>
    </div>
    <div>
    	<table class="fulltable">
    		<thead>
    			{$titleLines}
    		</thead>
    		<tbody>
    			{$resultLines}
    		</tbody>
    	
    	</table>
    </div>
{include file="common/my_footer.tpl"}
