{include file="common/main_header.tpl"}
{config_load file="words.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>{#manage#}</span></a></li>
        <li><a href="{admin_site_url('market_words/add')}" ><span>{#add#}</span></a></li>
      	<li><a href="{admin_site_url('market_words/import')}"><span>导入</span></a></li>
      	<li><a href="{admin_site_url('market_words/export')}"><span>导出</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
   <form class="formSearch" method="get" name="formSearch" id="formSearch" action="{admin_site_url('market_words')}">
    <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td><select name="search_field_name" >
              {foreach from=$search_map['search_field'] key=key item=item}
              <option value="{$key}" {if $smarty.get['search_field_name'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <td><input type="text" value="{$smarty.get['search_field_value']}" name="search_field_value" class="txt"></td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
   </form>
   
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5>操作提示</h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>通过{#title#}{#manage#}，你可以进行查看、编辑{#title#}资料以及删除{#title#}等操作</li>
            <li>你可以根据条件搜索{#title#}，然后选择相应的操作</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
   <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th>序号</th>
          <th>关键字</th>
          <th>推广链接</th>
          <th>排序</th>
          <th>创建时间</th>
          <th>最后修改时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover" id="row{$item['word_id']}">
          <td class="w24">{*<input type="checkbox" name="del_id[]" value="{$item['word_id']}"/>*}</td>
          <td>{$item['word_id']}</td>
          <td>{$item['word_name']|escape}</td>
          <td>{$item['word_url']|escape}</td>
          <td>{$item['word_sort']}</td>
          <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td>{$item['gmt_modify']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center">
          	<a href="{admin_site_url('market_words/edit')}?word_id={$item['word_id']}">编辑</a> |
          	<a href="{admin_site_url('market_words/detail')}?word_id={$item['word_id']}">详情</a> |
          	<a class="delete" href="javascript:void(0);" data-url="{admin_site_url('market_words/delete')}?word_id={$item['word_id']}" data-id="{$item['word_id']}">删除</a>
          </td>
        </tr>
      {/foreach}
      </tbody>
      <tfoot class="tfoot">
        <tr>
          <td colspan="8">
            {include file="common/pagination.tpl"}
        </tr>
      </tfoot>
    </table>
   	<script>
   		$(function(){
   			bindDeleteEvent();
   		});
   	</script>
{include file="common/main_footer.tpl"}