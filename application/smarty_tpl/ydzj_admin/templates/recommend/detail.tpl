{include file="common/main_header_navs.tpl"}
{config_load file="article.conf"}
  {form_open(site_url($uri_string),'id="formSearch"')}
	<style>

	.addbutton  {
    display:inline-block;
    cursor:pointer;
    font-size:14px;
    float:right;
    border: 1px solid #a6c9e2;
    padding:6px 10px;
    text-align:right;
    -webkit-border-radius:5px;
}
	</style>
	 <table class="tb-type1 noborder search" >
	 
	    <tbody>

	    	<tr>
	          <th><label for="name">名称</label></th>
	          <td><input class="txt" name="name" readonly value="{$recommendInfo['name']|escape}" type="text"></td>
	          <th><label for="name">风格</label></th>
	          <td><input class="txt" name="name" readonly value="{$recommendInfo['name']|escape}" type="text"></td>
	          <th><label for="name">显示条数</label></th>
	          <td><input class="txt" name="name" readonly value="{$recommendInfo['name']|escape}" type="text"></td>
	          <th><label for="name">模板名称</label></th>
	          <td><input class="txt" name="name" readonly value="{$recommendInfo['name']|escape}" type="text"></td>
			</tr>
			
	        <tr>
	        <th><label for="name">时间格式</label></th>
	          <td><input class="txt" name="name" readonly value="{$recommendInfo['name']|escape}" type="text"></td>
	          <th><label for="name">缓存时间</label></th>
	          <td><input class="txt" name="name" readonly value="{$recommendInfo['name']|escape}" type="text"></td>
	          <th><label for="name">标题最大字数</label></th>
	          <td><input class="txt" name="name" readonly value="{$recommendInfo['name']|escape}" type="text"></td>
	        <td>
				{if isset($permission[$moduleClassName|cat:'/add_detail'])}<a class="addbutton" href="{admin_site_url($moduleClassName|cat:'/add_detail')}?id={$item['id']}">添加</a>{/if}&nbsp;
	        </td>
	        </tr>    

	    </tbody>
	  </table>
	    <table class="table tb-type2">
          <thead>
	        <tr class="thead">
	        	 <th class="w24"></th>
				<th>{#display#}</th> 
				<th>{#title#}标题</th>
				<th>{#url#}</th>
				<th>{#synopsis#}</th>
				<th>{#release_time#}</th>
				<th>{#startdate#}</th>
				<th>{#enddate#}</th>
         		<th class="align-center">操作</th>
          </tr>
      	</thead>
      	{foreach from=$info item=item}
	      <tbody>
	        <tr> 
	        	<td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="id[]"></td>
				<td><span class="editable" data-id="{$item['id']}" data-fieldname="display">{$item['display']}</span></td>
				<td>{$item['title']|escape}</td>
				<td>{$item['url']|escape}</td>
				<td>{$item['synopsis']|escape}</td>
				<td>{$item['release_time']|escape}</td>
				<td>{$item['startdate']|escape}</td>
				<td>{$item['enddate']|escape}</td>
				<td class="align-center">
				{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if}&nbsp;
	        	</td>
	        </tr>  
	      </tbody>
        {/foreach}
	    </table>

	  </form>
  
      <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
        {include file="common/pagination.tpl"}
    </div>

  </form>
    <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
  <script>
  	var submitUrl = [new RegExp("{admin_site_url($moduleClassName|cat:'/dispatch')}"),new RegExp("{admin_site_url($moduleClassName|cat:'/complete_repair')}")];
    
	$(function(){
	    {if isset($permission[$moduleClassName|cat:'/delete'])}bindDeleteEvent();{/if}
	    
	    {if isset($permission[$moduleClassName|cat:'/inline_edit'])}
	    $("span.editable").inline_edit({ 
	    	url: "{admin_site_url($moduleClassName|cat:'/inline_edit')}",
	    	clickNameSpace:'inlineEdit'
	    });
	    {/if}
		    
	});
 
  </script>
