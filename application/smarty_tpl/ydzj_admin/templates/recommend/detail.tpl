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
    margin-right:20px;
}

。
.align-center{
	width:60px;
}

	</style>
	 <table class="tb-type1 noborder search" >
	 
	    <tbody>

	    	<tr>
	          <th><label for="name">名称</label></th>
	          <td><input class="txt" name="name" readonly value="{$recommendInfo['name']|escape}" type="text"></td>
	          <th><label for="name">风格</label></th>
	          <td><input class="txt" name="style" readonly value="{$basicData[$recommendInfo['style']]['show_name']|escape}" type="text"></td>
	          <th><label for="name">显示条数</label></th>
	          <td><input class="txt" name="show_number" readonly value="{$recommendInfo['show_number']|escape}" type="text"></td>
	          <th><label for="name">模板名称</label></th>
	          <td><input class="txt" name="template_name" readonly value="{$recommendInfo['template_name']|escape}" type="text"></td>
			</tr>
			
	        <tr>
	        <th><label for="name">时间格式</label></th>
	          <td><input class="txt" name="dateformat" readonly value="{$basicData[$recommendInfo['dateformat']]['show_name']|escape}" type="text"></td>
	          <th><label for="name">缓存时间</label></th>
	          <td><input class="txt"  name="cachetime" readonly value="{$recommendInfo['cachetime']|escape}分钟" type="text"></td>
	          <th><label for="name">标题最大字数</label></th>
	          <td><input class="txt" name="max_title" readonly value="{$recommendInfo['max_title']|escape}" type="text"></td>
		        <th></th>
		        <td>
		        	<a href="javascript:void(0);" class="addbutton opBtn " data-title="确定群发该素材吗？"  ids={$recommendInfo['id']} data-url="{admin_site_url($moduleClassName|cat:'/group_send')}?id={$recommendInfo['id']}"><span>群发素材</span></a>
		        	
		        	<a href="javascript:void(0);" class="addbutton opBtn " data-title="确认预览该素材吗？"  ids={$recommendInfo['id']} data-url="{admin_site_url($moduleClassName|cat:'/preview')}?id={$recommendInfo['id']}"><span>预览</span></a>
		        	
		        	<a href="javascript:void(0);" class="addbutton opBtn " data-title="确实刷新数据吗?刷新素材需要时间，请耐心等候"  ids={$recommendInfo['id']} data-url="{admin_site_url($moduleClassName|cat:'/uploadMaterial')}?id={$recommendInfo['id']}"  data-ajaxformid="#verifyForm"><span>刷新素材</span></a>
		        		
					{if isset($permission[$moduleClassName|cat:'/add_detail'])}<a class="addbutton" href="{admin_site_url($moduleClassName|cat:'/add_detail')}?id={$recommendInfo['id']}">添加</a>{/if}&nbsp;
		        	
		        </td>
	        <td>
	        	
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
				<td class="title"><a href="{admin_site_url(cms_article|cat:'/index')}?article_title={$item['title']}">{$item['title']|escape}</td>
				<td>{$item['url']|escape}</td>
				<td>{$item['synopsis']|escape}</td>
				<td>{date('Y-m-d h:i:s;',$item['release_time'])|escape}</td>
				<td>{date('Y-m-d h:i:s;',$item['startdate'])|escape}</td>
				<td>{date('Y-m-d h:i:s;',$item['enddate'])|escape}</td>
				<td class="align-center">
					{if isset($permission[$moduleClassName|cat:'/edit_detail'])}<a href="{admin_site_url($moduleClassName|cat:'/edit_detail')}?id={$item['id']}">编辑</a>{/if}&nbsp;
	        		{if isset($permission[$moduleClassName|cat:'/delete'])}<a class="delete" href="javascript:void(0);" data-url="{admin_site_url($moduleClassName|cat:'/delete')}?id={$item['id']}" data-id="{$item['id']}">删除</a>{/if} 
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
 <script type="text/javascript" src="{resource_url('js/recommend/upload.js',true)}"></script>
