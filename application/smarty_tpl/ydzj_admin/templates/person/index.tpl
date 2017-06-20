{include file="common/main_header.tpl"}
	{config_load file="common.conf"}
	{config_load file="person.conf"}
	<form action="{site_url($uri_string)}" method="get" id="formSearch">
        <input type="hidden" name="page" value=""/>
         <table class="table tb-type2 nobdb">
      		<tbody>
	            <tr>
	            	<td class="vatop ">
	                	<label class="ftitle">{#qlr_name#}</label>
	                	<input type="text" class="mtxt" name="qlr_name" value="{$smarty.get.qlr_name}"/>
	                	<label class="ftitle">{#id_no#}</label>
	                	<input type="text" name="id_no" value="{$smarty.get.id_no}" placeholder="请输入身份证号码"/>
		                <label class="ftitle">{#sex#}</label>
		                <select name="sex">
		                    <option value="0" {if $smarty.get.sex == 0}selected{/if}>不限</option>
		                    <option value="1" {if $smarty.get.sex == 1}selected{/if}>男</option>
		                    <option value="2" {if $smarty.get.sex == 2}selected{/if}>女</option>
		                </select>
		                <label class="ftitle">{#person_yhdz#}</label>
		                <select name="yhdz">
		                    <option value="" {if $smarty.get.yhdz == 0}selected{/if}>不限</option>
		                    <option value="是" {if $smarty.get.yhdz == '是'}selected{/if}>是</option>
		                </select>
		                <input class="msbtn" type="submit" name="search" value="查询"/>
		            </td>
	            </tr>
	         </tbody>
		  </table>
        {include file="./list.tpl"}
	    <div class="align-right">{include file="common/pagination.tpl"}</div>
    </form>
{include file="common/main_footer.tpl"}

