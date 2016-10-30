{include file="common/my_header.tpl"}
  {config_load file="lab_goods.conf"}
  <div class="rel">
	  <form name="formSearch" id="formSearch" action="{site_url($uri_string)}" method="get">
	  <input type="hidden" name="page" value=""/>
	    <table class="fulltable noborder noext">
	      <tbody>
	      	<tr>
	          <td><label>{#lab_name#}:</label></td>
	          <td><input type="text" class="txt" name="lab_name" value="{$smarty.get.lab_name}" placeholder="请输入{#lab_name#}" /></td>
	          <td><label>{#goods_name#}:</label></td>
	          <td><input type="text" class="txt" name="name" value="{$smarty.get.name}" placeholder="请输入{#goods_name#}" /></td>
	          <td><label>{#subject_name#}:</label></td>
	          <td><input type="text" class="txt" name="subject_name" value="{$smarty.get.subject_name}" placeholder="请输入{#subject_name#}" /></td>
	     </tr>
	     <tr>
	          <td><label>分类:</label></td>
	          <td><select class="form_select" name="category_id">
	            <option value="0">全部</option>
	            {foreach from=$categoryList item=item key=key}
	            <option value="{$key}" {if $key == $smarty.get.category_id}selected{/if}>{$item['sep']}{$item['name']|escape}</option>
	            {/foreach}
	          </select>
	          </td>
	          <td>已达到预警:</td>
	          <td>
	            <label><input type="radio" name="threshold_active" value="" {if $smarty.get.threshold_active ==''}checked{/if}/>全部</label>
	            <label><input type="radio" name="threshold_active" value="y" {if $smarty.get.threshold_active =='y'}checked{/if}/>是</label>
	            <label><input type="radio" name="threshold_active" value="n" {if $smarty.get.threshold_active =='n'}checked{/if}/>否</label>
	          </td>
	          <td colspan="2"><input type="submit" class="master_btn" value="查询" />
	              <a href="{site_url('lab_goods/export/?')}{$queryStr}" title="导出到EXCEL">导出到EXCEL</a>
	              <span class="tip">&lt;&lt;&lt;请右键目标另存为</span></td>
	       	</tr>
	      </tbody>
	    </table>
	    <a href="{site_url('lab_goods/add')}" class="action sideadd">添加货品</a>
	    <div id="goodslist" class="rounded-corner">
		  <table class="fulltable">
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
			            <th class="first">实验室名称</th>
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
			        <tr id="row{$item['id']}" data-url="{site_url('lab_goods/edit?id=')}{$item['id']}">
			            <td>{$item['lab_name']|escape}</td>
			            <td>{$item['code']|escape}</td>
			            <td><a class="popwin asblock" data-url="{site_url('lab_goods/info?id=')}{$item['id']}" data-title="{$item['name']|escape}" href="javascript:void(0);">{$item['name']|escape}</a></td>
			            <td>{$item['specific']|escape}</td>
			            <td {if $item['threshold'] > 0 && $item['threshold'] >= $item['quantity']}class="warning" title="低库存 阀值{$item['threshold']}"{/if}>{$item['quantity']|escape}{$item['measure']|escape}</td>
			            <td>{$item['price']|escape}</td>
			            <td>{$item['category_name']|escape}</td>
			            <td>{$item['subject_name']|escape}</td>
			            <td>{$item['remark']|escape}</td>
			            <td>
			            	<div>{$item['creator']|escape}</div>
			            	<div>{time_tran($item['gmt_create'])}</div>
			            </td>
			            <td>
			            	<div>{$item['updator']|escape}</div>
			            	<div>{time_tran($item['gmt_modify'])}</div>
			            </td>
			            <td>
			                <a href="{site_url('lab_goods/edit?id=')}{$item['id']}">编辑</a>&nbsp;
			                <a class="delete" href="javascript:void(0);" data-id="{$item['id']}" data-url="{site_url('lab_goods/delete?id=')}{$item['id']}" data-title="x{$item['name']|escape}">删除</a>
			            </td>
			        </tr>
			        {foreachelse}
			        <tr><td colspan="13" align="center">找不到记录</td></tr>
			        {/foreach}  
			    </tbody>
			    <tfoot>
			        <tr>
			            <td colspan="13">{include file="common/pagination.tpl"}</td>
			        </tr>
			    </tfoot>
			</table>
		 </div>
	  </form>
  </div>
  {include file="common/jquery_ui.tpl"}
  <div id="goodsDetail" title="{#title#}详情"></div>
<script>
$(function(){
    bindDeleteEvent();
    
    $.loadingbar({ urls: [ new RegExp("{site_url('lab_goods/info') }")], templateData:{ message:"努力加载中..." } , container: "#goodslist" });
    
    /*
    $(".rounded-corner tr").bind("dblclick",function(){
    	var url = $(this).attr('data-url');
    	location.href=url;
    });
    */
    
    $("a.popwin").bind("click",function(){
    	var url = $(this).attr('data-url');
    	var dialog = $( "#goodsDetail" ).dialog({
		      autoOpen: false,
		      height: '600',
		      width: '50%',
		      modal: true
		});
		
		$.ajax({
			url:url,
			dataType:'html',
			cache:false,
			success:function(resp){
				dialog.html(resp).dialog( "open" );
			}
		});
    });
});
</script>
{include file="common/my_footer.tpl"}