{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>货品</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="{admin_site_url('goods/add')}"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('lab_goods/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
      	<tr>
          <td><label>实验室地址:</label></td>
          <td><input type="text" class="txt" name="lab_address" value="{$smarty.get.lab_address}" placeholder="请输入实验室地址" /></td>
          <td><label>货品名称:</label></td>
          <td><input type="text" class="txt" name="name" value="{$smarty.get.name}" placeholder="请输入货品名称" /></td>
          <td><label>实验名称/课程名称:</label></td>
          <td><input type="text" class="txt" name="subject_name" value="{$smarty.get.subject_name}" placeholder="请输入实验名称/课程名称" /></td>
     </tr>
     <tr>
          <td><label>备注:</label></td>
          <td><input type="text" class="txt" name="project_name" value="{$smarty.get.project_name}" placeholder="请输入备注" /></td>
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
              <a href="{base_url('lab_goods/export/?')}{$queryStr}" title="导出到EXCEL">导出到EXCEL</a>
              <span class="tip">&lt;&lt;&lt;请右键目标另存为</span></td>
       	</tr>
      </tbody>
    </table>
  </form>
  {*
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div>
        </th>
      </tr>
      <tr>
        <td>
        	<ul>
              <li>上架，当商品处于非上架状态时，前台将不能浏览该商品，管理员可控制商品上架状态</li>
            </ul>
        </td>
      </tr>
    </tbody>
  </table>
  *}
  <table class="table tb-type2">
	    <colgroup>
	        <col style="width:10%"/>
	        <col style="width:8%"/>
	        <col style="width:10%"/>
	        <col style="width:5%"/>
	        <col style="width:5%"/>
	        <col style="width:5%"/>
	        <col style="width:5%"/>
	        <col style="width:8%"/>
	        <col style="width:10%"/>
	        <col style="width:10%"/>
	        <col style="width:5%"/>
	        <col style="width:10%"/>
	        <col style="width:5%"/>
	    </colgroup>
	    <thead>
	        <tr>
	            <th>实验室地址</th>
	            <th>货品柜/试验台</th>
	            <th>名称</th>
	            <th>规格</th>
	            <th>单位</th>
	            <th>库存</th>
	            <th>参考价格</th>
	            <th>类别</th>
	            <th>实验名称/课程名称</th>
	            <th>备注</th>
	            <th>录入人</th>
	            <th>录入时间</th>
	            <th>操作</th>
	        </tr>
	    </thead>
	    <tbody>
	        {foreach from=$data['data'] key=key item=item}
	        <tr id="row_{$item['id']}" class="{if $key % 2 == 0}odd{else}even{/if}">
	            <td>{$item['lab_address']|escape}</td>
	            <td>{$item['code']|escape}</td>
	            <td><a class="popwin asblock" data-width="500"  data-href="{base_url('lab_goods/info/id/')}{$item['id']}" data-title="{$item['name']|escape}" href="javascript:void(0);">{$item['name']|escape}</a></td>
	            <td>{$item['specific']|escape}</td>
	            <td>{$item['measure']|escape}</td>
	            <td {if $item['threshold'] > 0 && $item['threshold'] >= $item['quantity']}class="warning" title="低库存 阀值{$item['threshold']}"{/if}>{$item['quantity']|escape}</td>
	            <td>{$item['price']|escape}</td>
	            <td>{$item['category_name']|escape}</td>
	            <td>{$item['subject_name']|escape}</td>
	            <td>{$item['project_name']|escape}</td>
	            <td>{$item['creator']|escape}</td>
	            <td>{time_tran($item['gmt_create'])}</td>
	            <td>
	            {* 如果是系统级 管理员 ， 但不是该货品实验室管理员 只能修改 *}
	            {if $isSystemManager || in_array($item['lab_id'],$joinedLabs)}
	                <a href="{base_url('lab_goods/edit/id/')}{$item['id']}">编辑</a>&nbsp;
	            {/if}
	            {if in_array($item['lab_id'],$managedLabs)}
	                <a class="delete" href="javascript:void(0);" data-href="{base_url('lab_goods/delete/id/')}{$item['id']}" data-title="确定删除{$item['name']|escape}吗?">删除</a>
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
<script>
$(function(){
    bindDeleteEvent();
});
</script>
{include file="common/main_footer.tpl"}