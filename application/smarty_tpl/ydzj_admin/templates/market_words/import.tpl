{include file="common/main_header.tpl"}
 {config_load file="words.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('market_words/index')}"><span>管理</span></a></li>
      	<li><a href="{admin_site_url('market_words/add')}"><span>新增</span></a></li>
      	<li><a class="current"><span>导入</span></a></li>
      	<li><a href="{admin_site_url('market_words/export')}"><span>导出</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {form_open_multipart(admin_site_url('market_words/import'),'name="form1"')}
    <input type="hidden" name="charset" value="gbk" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>请选择文件:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-box">
            <input type="file" name="csv" id="csv" class="type-file-file"  size="30"  />
            </span></td>
          <td class="vatop tips">如果导入速度较慢，建议您把文件拆分为几个小文件，然后分别导入</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>文件格式:</label>
            <a href="{resource_url('example/words.csv')}" class="btns"><span>点击下载导入例子文件</span></a></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan="2"><table border="1" cellpadding="3" cellspacing="3" bordercolor="#CCC">
              <tbody>
                <tr>
                  <td bgcolor="#FFFFEC">关键字</td>
                  <td bgcolor="#FFFFEC">外链地址</td>
                </tr>
                <tr>
                  <td bgcolor="#EFF8F8">白银</td>
                  <td bgcolor="#EFF8F8">http://s1.txcf188.com/#PC-gp-cs-xin-00001</td>
                </tr>
                <tr>
                  <td bgcolor="#EFF8F8">黄金</td>
                  <td bgcolor="#EFF8F8">http://s1.txcf188.com/#PC-gp-cs-xin-00002</td>
                </tr>
              </tbody>
            </table></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="4"><input type="submit" name="submit" value="开始导入" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  
  <script type="text/javascript">
	$(function(){
    	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />"
		$(textButton).insertBefore("#csv");
		$("#csv").change(function(){
			$("#textfield1").val($("#csv").val());
		});
	});
</script>
{include file="common/main_footer.tpl"}