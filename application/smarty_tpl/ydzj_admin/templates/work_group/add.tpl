{include file="common/main_header_navs.tpl"}
  {if $info['id']}
  {form_open(site_url($uri_string),'id="add_form"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="add_form"')}
  {/if}
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">组名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="group_name" value="{$info['group_name']|escape}" name="group_name" class="txt"></td>
          <td class="vatop tips"><label id="error_group_name" class="errtip"></label>{form_error('group_name')} 请输入组名称 </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">组长手机:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="group_leader_mobile" value="{$info['group_leader_mobile']|escape}" name="group_leader_mobile" class="txt"></td>
        </tr>
        
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">组长名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="group_leader_name" value="{$info['group_leader_name']|escape}" name="group_leader_name" class="txt"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation">服务区域:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan="2">
          	<ul class="">

			{foreach from=$serviceArea key=Key item=Item}
  			<li><label><input type="checkbox" name="service_area[]" value="{$Item['id']}" {if $inPost}{set_checkbox('service_area[]',$Item['id'])}{elseif $info['service_area']}{if in_array($Item['id'],$info['service_area'])}checked="checked"{/if}{/if}/>{$Item['show_name']|escape}</label></li>
  			{/foreach}
	        </ul>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2"><label class="validation">组员: </label></td>
        </tr>
        <tr>
	    	<td colspan="2">
	    		<table id="workGroupTable">
	    			<thead>
	    				<tr>
	    					<th>组员电话</th>
	    					<th>组员姓名</th>
	    				</tr>
	    			</thead>
	    			<tbody>
	    				<tr>
	    					<td>
								<input type="text" {if !empty($memberList[0]['worker_mobile'])} value={$memberList[0]['worker_mobile']}{/if}  name="worker_mobile[]"  class="txt worker_mobile">
	    					</td>
	    					<td>
	    						<input type="text"  {if !empty($memberList[0]['worker_name'])} value={$memberList[0]['worker_name']}{/if} name="worker_name[]" class="txt"></td>
	    					<td>
	    						<input type="button" class="dynBtn" name="addBtn" value="添加"/>
	    					</td>
	    				</tr>
	    				{if $memberList}
						  {foreach from=$memberList key=key item=valus}
						  	{if $memberList[$key]['worker_mobile'] != $memberList[0]['worker_mobile']}
        				<tr>
	    					<td>
								<input type="text" value={$valus['worker_mobile']}  name="worker_mobile[]"  class="txt">
	    					</td>
	    					<td >
	    						<input type="text"  value={$valus['worker_name']}  name="worker_name[]" class="txt">
	    					</td>
							<td>
								<input type="button" class="dynBtn" name="deleteBtn" value="删除"/>
							</td>
						</tr>
							{/if}
						  {/foreach}
						{/if}
					</tbody>
				</table>
			</td>
			</tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2">
          	<input type="submit" name="submit" value="保存" class="msbtn"/>
          	{if $gobackUrl}
	    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
	    	{/if}
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  	<script type="x-template" id="templateRow">
		<tr>
			<td>
				<input type="text" value=""  name="worker_mobile[]"  class="txt worker_mobile">
			</td>
			<td>
				<input type="text"  value="" name="worker_name[]" class="txt">
			</td>
			<td>
				<input type="button" class="dynBtn" name="deleteBtn" value="删除"/>
			</td>
		</tr>
  </script>
  <script>
  	$(function(){
  		$.loadingbar({ text: "正在提交..." , urls: [ new RegExp("{$uri_string}") ] , container : "#add_form" });
  		
  		$(".datepicker").datepicker();
  		
  		bindAjaxSubmit("#add_form");
  		
		$("body").delegate('.dynBtn','click',function(){
		
			var that = $(this);
			console.log(that);
			
			var html = $("#templateRow").html();
			
			//html.replace(/\[\]/,'\\[\\]');
			
			var newRow = $(html);
			
			if(that.val() == '添加'){
				$("#workGroupTable tbody").append(newRow);
			}else{
				
				if(that.parent().parent().index() != 0){
					that.parent().parent().remove();
				}
			}
		});
  	});
  </script>
{include file="common/main_footer.tpl"}