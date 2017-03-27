{include file="common/main_header.tpl"}
<div class="fixed-bar">
    <div class="item-title">
      <h3>板块配置</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('block/index')}"><span>板块区</span></a></li>
        <li><a {if empty($info['block_id'])}class="current"{/if} href="{admin_site_url('block/add')}"><span>添加板块</span></a></li>
        {if $info['block_id']}<li><a class="current"  href="{admin_site_url('block/edit?block_id='|cat:$info['block_id'])}"><span>板块基本信息编辑</span></a></li>{/if}
        {*<li><a href="index.php?act=web_config&op=code_edit&web_id=1"><span>板块编辑</span></a></li>*}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['block_id']}
  {form_open(admin_site_url('block/edit'),'id="add_form"')}
  {else}
  {form_open(admin_site_url('block/add'),'id="add_form"')}
  {/if}
    <input type="hidden" name="id" value="{$info['id']}"/>
    <input type="hidden" name="block_id" value="{$info['block_id']}" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">板块名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="block_name" name="block_name" value="{$info['block_name']|escape}" class="txt" type="text"></td>
          <td class="vatop tips">板块名称只在后台首页模板设置中作为板块标识出现，在前台首页不显示。</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">色彩风格:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" style=" height:48px;">
            <input type="hidden" value="{$info['style_name']}" name="style_name" id="style_name">
            <ul class="home-templates-board-style">
              <li class="red"><em></em><i class="icon-ok"></i>红色</li>
              <li class="pink"><em></em><i class="icon-ok"></i>粉色</li>
              <li class="orange"><em></em><i class="icon-ok"></i>橘色</li>
              <li class="green"><em></em><i class="icon-ok"></i>绿色</li>
              <li class="blue"><em></em><i class="icon-ok"></i>蓝色</li>
              <li class="purple"><em></em><i class="icon-ok"></i>紫色</li>
              <li class="brown"><em></em><i class="icon-ok"></i>褐色</li>
              <li class="gray"><em></em><i class="icon-ok"></i>灰色</li>
            </ul></td>
          <td class="vatop tips">选择板块色彩风格将影响商城首页模板该区域的边框、背景色、字体色彩，但不会影响板块的内容布局。</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label>
            </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['displayorder']}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">数字范围为0~255，数字越小越靠前</td>
        </tr>
        <tr>
          <td colspan="2" class="required">显示:
            </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
            <label for="show1" class="cb-enable {if $info['is_show'] == 1}selected{/if}" title="是"><span>是</span></label>
            <label for="show0" class="cb-disable {if $info['is_show'] == 0}selected{/if} title="否"><span>否</span></label>
            <input id="show1" name="is_show" {if $info['is_show'] == 1}checked="checked"{/if} value="1" type="radio">
            <input id="show0" name="is_show" {if $info['is_show'] == 0}checked="checked"{/if} value="0" type="radio"></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
   <script>
    $(function(){
    	{if $info['style_name']}
        $(".home-templates-board-style .{$info['style_name']}").addClass("selected");
    	{/if}
    	
        $(".home-templates-board-style li").click(function(){
            $(".home-templates-board-style li").removeClass("selected");
            $("#style_name").val($(this).attr("class"));
            
            $(this).addClass("selected");
        });
    });
   
   </script>
{include file="common/main_footer.tpl"}