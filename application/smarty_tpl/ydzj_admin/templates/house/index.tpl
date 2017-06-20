{include file="common/main_header.tpl"}
	{config_load file="common.conf"}
	{config_load file="person.conf"}
	<form action="{admin_site_url('house/index')}" method="get" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <table class="table tb-type2 nobdb">
      		<tbody>
	            <tr>
	            	<td class="vatop ">
		                <label class="ftitle">{#qlr_name#}</label>
		                <input type="text" class="mtxt" name="qlr_name" value="{$smarty.get.qlr_name}"/>
		                <label class="ftitle">{#id_no#}</label>
		                <input type="text" name="id_no" value="{$smarty.get.id_no}" placeholder="请输入身份证号码"/>
		                <input class="msbtn" type="submit" name="search" value="查询"/>
		            </td>
		         </tr>
		     </tbody>
         </table>
        {include file="./list.tpl"}
	    <div class="align-right">{include file="common/pagination.tpl"}</div>
    </form>
    
{include file="common/main_footer.tpl"}

