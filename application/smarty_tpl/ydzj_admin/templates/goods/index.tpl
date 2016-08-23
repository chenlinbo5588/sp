{include file="common/main_header.tpl"}
  {config_load file="goods.conf"}
  {include file="./goods_common.tpl"}
  <div class="feedback">{$feedback}</div>
  <div class="fixed-empty"></div>
  <form name="formSearch" id="formSearch" action="{admin_site_url('goods/index')}" method="get">
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
      	<tr>
          <td><label>{#address#}:</label></td>
          <td><input type="text" class="txt" name="lab_address" value="{$smarty.get.lab_address}" placeholder="请输入{#address#}" /></td>
          <td><label>{#goods_name#}:</label></td>
          <td><input type="text" class="txt" name="name" value="{$smarty.get.name}" placeholder="请输入{#goods_name#}" /></td>
          <td><label>{#subject_name#}:</label></td>
          <td><input type="text" class="txt" name="subject_name" value="{$smarty.get.subject_name}" placeholder="请输入{#subject_name#}" /></td>
     </tr>
     <tr>
          <td><label>{#project_name#}:</label></td>
          <td><input type="text" class="txt" name="project_name" value="{$smarty.get.project_name}" placeholder="请输入{#project_name#}" /></td>
          <td><label>分类:</label></td>
          <td><select class="form_select" name="category_id">
            <option value="0">全部</option>
            {foreach from=$categoryList item=item key=key}
            <option value="{$key}" {if $key == $smarty.get.category_id}selected{/if}>{$item['sep']}{$item['name']|escape}</option>
            {/foreach}
          </select>
          </td>
          <td><label>已达到预警:<input type="checkbox" name="threshold_active" value="y" {if $smarty.get.threshold_active}checked{/if}/></label></td>
          <td><input type="submit" class="msbtn" value="查询" />
              <a href="{admin_site_url('goods/export/?')}{$queryStr}" title="导出到EXCEL">导出到EXCEL</a>
              <span class="tip">&lt;&lt;&lt;请右键目标另存为</span></td>
       	</tr>
      </tbody>
    </table>
  </form>
  
  <table class="rounded-corner">
  		{*
	    <colgroup>
	        <col style="width:10%"/>
	        <col style="width:8%"/>
	        <col style="width:10%"/>
	        <col style="width:5%"/>
	        <col style="width:3%"/>
	        <col style="width:5%"/>
	        <col style="width:5%"/>
	        <col style="width:8%"/>
	        <col style="width:10%"/>
	        <col style="width:10%"/>
	        <col style="width:5%"/>
	        <col style="width:8%"/>
	        <col style="width:8%"/>
	        <col style="width:5%"/>
	    </colgroup>
	    *}
	    <thead>
	        <tr>
	            <th class="first">实验室地址</th>
	            <th>货品柜/试验台</th>
	            <th>名称</th>
	            <th>规格</th>
	            <th>库存</th>
	            <th>参考价格(元)</th>
	            <th>类别</th>
	            <th>实验名称/课程名称</th>
	            <th>备注</th>
	            <th>
	            	<div>录入人</div>
	            	<div>录入时间</div>
	            </th>
	            <th>
	            	<div>修改人</div>
	            	<div>最后更新时间</div>
	            </th>
	            <th class="last">操作</th>
	        </tr>
	    </thead>
	    <tbody>
	        {foreach from=$data['data'] key=key item=item}
	        <tr id="row_{$item['id']}" class="{if $key % 2 == 0}odd{else}even{/if}">
	            <td>{$item['lab_address']|escape}</td>
	            <td>{$item['code']|escape}</td>
	            <td><a class="popwin asblock" data-width="500"  data-href="{admin_site_url('lab_goods/info/id/')}{$item['id']}" data-title="{$item['name']|escape}" href="javascript:void(0);">{$item['name']|escape}</a></td>
	            <td>{$item['specific']|escape}</td>
	            <td {if $item['threshold'] > 0 && $item['threshold'] >= $item['quantity']}class="warning" title="低库存 阀值{$item['threshold']}"{/if}>{$item['quantity']|escape}{$item['measure']|escape}</td>
	            <td>{$item['price']|escape}</td>
	            <td>{$item['category_name']|escape}</td>
	            <td>{$item['subject_name']|escape}</td>
	            <td>{$item['project_name']|escape}</td>
	            <td>
	            	<div>{$item['creator']|escape}</div>
	            	<div>{time_tran($item['gmt_create'])}</div>
	            </td>
	            <td>
	            	<div>{$item['updator']|escape}</div>
	            	<div>{time_tran($item['gmt_modify'])}</div>
	            </td>
	            <td>
	            {* 如果是系统级 管理员 ， 但不是该货品实验室管理员 只能修改 *}
	            {if $isSystemManager || in_array($item['lab_id'],$joinedLabs)}
	                <a href="{admin_site_url('goods/edit?id=')}{$item['id']}">编辑</a>&nbsp;
	            {/if}
	            {if in_array($item['lab_id'],$managedLabs)}
	                <a class="delete" href="javascript:void(0);" data-id="{$item['id']}" data-url="{admin_site_url('lab_goods/delete/id/')}{$item['id']}" data-title="确定删除{$item['name']|escape}吗?">删除</a>
	            {/if}
	            </td>
	        </tr>
	        {foreachelse}
	        <tr><td colspan="13" align="center"><span class="warning">找不到记录</span></td></tr>
	        {/foreach}  
	    </tbody>
	    <tfoot>
	        <tr>
	            <td colspan="13">{include file="common/pagination.tpl"}</td>
	        </tr>
	    </tfoot>
	</table>
  </form>
  {include file="common/jquery_ui.tpl"}
<script>
$(function(){
    bindDeleteEvent();
});
</script>
{include file="common/main_footer.tpl"}