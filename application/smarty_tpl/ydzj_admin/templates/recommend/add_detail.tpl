{include file="common/main_header_navs.tpl"}
  {config_load file="article.conf"}
  {form_open(site_url($uri_string),'id="infoform"')}
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
          <td class="vatop rowform"><input type="text" value="{$info['img_url']|escape}" name="img_url" id="img_url" class="txt"></td>
        </tr>
        
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
          <td class="vatop rowform"><input type="text" value="{$info['release_time']|escape}" name="release_time" id="release_time" class="txt"></td>
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
          <td class="vatop rowform"><input type="text" value="{$info['startdate']|escape}" name="startdate" id="startdate" class="datepicker"></td>
        </tr>
        
 	 	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="enddate">{#enddate#}</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['enddate']|escape}" name="enddate" id="enddate" class="datepicker"></td>
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
	
	
	
	
  </form>
  <script type="text/javascript">
	var submitUrl = [new RegExp("{$uri_string}")],searchTitle = "{admin_site_url('recommend/gettitle')}";
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		$( ".datepicker" ).datepicker();
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