{include file="common/main_header.tpl"}
<style type="text/css">
	.current { color: red; }
</style>
  <div class="fixed-bar">
    <div class="item-title">
      <h3>快递公司</h3>
      <ul class="tab-base"><li><a class="current"><span>快递公司</span></a></li></ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
	<table class="tb-type1 noborder search">
	  <tbody>
	    <tr>
	      <th><label for="search_brand_name">首字母</label></th>
	      <td>
	      	<a {if $letter == ''}class="current"{/if} href="{admin_site_url('setting/express')}">全部</a>&nbsp;&nbsp;
	      	{foreach from=$charList item=item}
	      		<a {if $letter == $item}class="current"{/if} href="{admin_site_url('setting/express')}?letter={$item}">{$item}</a>&nbsp;&nbsp;
	      	{/foreach}
	      </td>
	    </tr>
	  </tbody>
	</table>
	<form method="get" name="formSearch" id="formSearch" action="{admin_site_url('setting/express')}">
	<input type="hidden" name="page" value="{$currentPage}"/>
	<input type="hidden" name="letter" value="{$letter}"/>
	<table class="table tb-type2" id="prompt">
		<tbody>
		  <tr class="space odd">
		    <th colspan="6"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
		  </tr>
		  <tr>
		    <td>
		    <ul>
		        <li>系统内置的快递公司不得删除，只可编辑状态，平台可禁用不需要的快递公司，默认按首字母进行排序，常用的快递公司将会排在靠前位置</li>
		        <!--<li>更改状态后，需要到 设置 -> 清理缓存 中，清理快递公司缓存 后，才会生效</li>-->
		      </ul></td>
		  </tr>
		</tbody>
		  </table>
		<table class="table tb-type2">
		  <thead>
		    <tr class="thead">
		      <th class="w24"></th>
		      <th class="w270">快递公司</th>
		      <th >首字母</th>
		      <th class="w270">网址 (仅供参考)</th>
		      <th class="align-center">常用</th>
		      <th class="align-center">状态</th>
		    </tr>
		  </thead>
		  <tbody>
		  	{foreach from=$list['data'] item=item}
		  	<tr class="hover" id="{$item['id']}">
			     <td></td>
			     <td>{$item['name']}</td>
			     <td>{$item['letter']}</td>
			     <td>{$item['url']}</td>
			     <td class="align-center yes-onoff">
			      	<a href="JavaScript:void(0);" class="{if $item['isfreq'] == 1}enabled{else}disabled{/if}" data-fieldname="isfreq">&nbsp;</a>
			     </td>     
			     <td class="align-center yes-onoff">
			      	<a href="JavaScript:void(0);" class="{if $item['state'] == 1}enabled{else}disabled{/if}" data-fieldname="state">&nbsp;</a>
			     </td>
			</tr>
			{/foreach}
		    </tbody>
		  	<tfoot>
		  		<tr class="tfoot">
		  			<td colspan="6">{include file="common/pagination.tpl"}</td>
		    	</tr>
		    </tfoot>      
		</table>
	</form>
   </div>
<script>
	$(function(){
		$(".yes-onoff a").bind("click",function(){
			var fieldname = $(this).attr('data-fieldname');
			var enabled = 0;
			var expressId = $(this).parents("tr").attr("id");
			var that = $(this);
			
			if($(this).hasClass("enabled")){
				enabled = 0;
			}else{
				enabled = 1;
			}
			
			$.post("{admin_site_url('setting/express')}",{ formhash : formhash , fieldname : fieldname, enabled : enabled , id : expressId } , function(json){
				if(json.message = '保存成功'){
					if(that.hasClass("enabled")){
						that.removeClass("enabled").addClass("disabled");
					}else{
						that.removeClass("disabled").addClass("enabled");
					}
				}
				
				refreshFormHash(json.data);
			} , 'json');
		});
		
	});

</script>
{include file="common/main_footer.tpl"}