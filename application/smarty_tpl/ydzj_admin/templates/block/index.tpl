{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>板块管理</h3>
      <ul class="tab-base">
        <li><a class="current" href="{admin_site_url('block/index')}"><span>板块区</span></a></li>
        <li><a href="{admin_site_url('block/add')}"><span>添加板块</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('block/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>排序越小越靠前，可以控制板块显示先后。</li>
            <li>色彩风格和前台的样式一致，在基本设置中选择更换。</li>
            <li>色彩风格是css样式中已经有的，如果需要修改名称则相关程序也要同时改变才会有效果。</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>排序</th>
          <th>板块名称</th>
          <th>色彩风格</th>
          <th class="align-center">更新时间</th>
          <th class="align-center">显示</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
         {foreach from=$list['data'] item=item}
         <tr class="hover">
          <td class="w48 sort">{$item['displayorder']}</td>
          <td>{$item['block_name']|escape}</td>
          <td>{$item['style_name']}</td>
          <td class="w150 align-center">{$item['gmt_modify']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="w150 align-center">{if $item['is_show'] == 1}是{else}否{/if}</td>
          <td class="w150 align-center">
            <a href="{admin_site_url('block/edit?block_id='|cat:$item['block_id'])}">基本设置</a>{* | 
            <a href="index.php?act=web_config&op=code_edit&web_id=1">板块编辑</a></td> *}
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
        <tr class="tfoot">
            <td colspan="6">{include file="common/pagination.tpl"}</td>
        </tr>
	   </tfoot>
    </table>
{include file="common/main_footer.tpl"}