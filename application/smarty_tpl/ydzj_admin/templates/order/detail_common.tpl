       	<tr>
   		  <td class="required">{#customer_name#}: </td>
          <td class="vatop rowform">{$info['username']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#mobile#}: </td>
          <td class="vatop rowform">{$info['mobile']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#ref_order#}: </td>
          <td class="vatop rowform">{$info['ref_order']}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#order_old#}: </td>
          <td class="vatop rowform">{$info['order_old']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#order_typename#}: </td>
          <td class="vatop rowform">{$info['order_typename']}{if $info['is_refund']}退款{/if}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#pay_channel#}: </td>
          <td class="vatop rowform">{$info['pay_channel']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#pay_method#}: </td>
          <td class="vatop rowform">{$info['pay_method']|escape}</td>      
       	</tr>
      	<tr>
   		  <td class="required">{#goods_name#}: </td>
          <td class="vatop rowform">{$info['goods_name']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#amount#}: </td>
          <td class="vatop rowform">{$info['amount']/100|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#refund_amount#}: </td>
          <td class="vatop rowform">{$info['refund_amount']/100|escape}</td>      
       	</tr>
       	{if !$info['is_refund']}
       	<tr>
   		  <td class="required">{#refund_cnt#}: </td>
          <td class="vatop rowform">{$info['refund_cnt']}</td>      
       	</tr>
       	{/if}
       	<tr>
   		  <td class="required">{#status#}: </td>
          <td class="vatop rowform"><strong class="orange">{$OrderStatus[$info['status']]|escape}</strong></td>      
       	</tr>
       	<tr>
   		  <td class="required">{#order_time#}: </td>
          <td class="vatop rowform">{$info['gmt_create']|escape|date_format:"%Y-%m-%d %H:%M:%S"}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#time_expire#}: </td>
          <td class="vatop rowform">{$info['time_expire']|escape|date_format:"%Y-%m-%d %H:%M:%S"}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#pay_time_end#}: </td>
          <td class="vatop rowform">{$info['pay_time_end']|escape|date_format:"%Y-%m-%d %H:%M:%S"}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#ip#}: </td>
          <td class="vatop rowform">{$info['ip']|escape}</td>      
       	</tr>
       	<tr>
   		  <td class="required">{#verify_status#}: </td>
          <td class="vatop rowform"><strong class="orange">{$OrderVerify[$info['verify_status']]}</strong></td>      
       	</tr>
       	{foreach from=$extraItem item=item}
       	<tr>
   		  <td class="required">{$item[0]}: </td>
          <td class="vatop rowform">{$item[1]}</td>
       	</tr>
       	{/foreach}