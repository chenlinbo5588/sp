  {config_load file="wuye.conf"}
  
  {foreach from=$list item=item}
  <div>
  		<h1>{$item['feetype_name']|escape}</h1>
	  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
	  	 <input type="hidden" name="page" value="{$currentPage}"/>
		 <table class="tb-type1 noborder search" >
		    <tbody>
		        <tr>
		        </tr>
		    </tbody>
		  </table>
	    <table class="table tb-type2">
	      <tbody>
	        <tr class="thead">  
	          <th>{#jz_area#}</th>
	          <th>{#price#}</th>
	          <th>{#year#}</th>
	          <th>{#billing_style#}</th>
	          <th>{#month#}</th>
	          <th>{#amount_plan#}</th>
	          <th>{#amount_real#}</th>
	          <th>{#amount_payed#}</th>
	        </tr>
	        <tr>  
	          <td>{$item['jz_area']|escape}</td>
	          <td>{$item['price']|escape}</td>
	          <td>{$item['year']|escape}</td>
	          <td>{$item['billing_style']|escape}</td>
	          <td>{$item['month']|escape}</td>
	          <td>{$item['amount_plan']|escape}</td>
	          <td><span class="editable" data-id="{$item['id']}" data-fieldname="really_plan_money">{$item['amount_real']|escape}</span></td>
	          <td>{$item['amount_payed']|escape}</td>
	        </tr>  
	      </tbody>
	    </table>

	  </form>
  </div>
  {/foreach}
  </form>
  <script>
  	var submitUrl = [new RegExp("{admin_site_url($moduleClassName|cat:'/dispatch')}"),new RegExp("{admin_site_url($moduleClassName|cat:'/complete_repair')}")];
  	var editUrl = "{admin_site_url($moduleClassName|cat:'/inline_edit')}"; 	
  </script>
  <script type="text/javascript" src="{resource_url('js/wuye/repair_index.js',true)}"></script>
