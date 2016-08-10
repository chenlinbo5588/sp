{include file="common/lab_admin_header.tpl"}
    <div class="submenu">
        <div class="clear"></div>
    </div>
    <style type="text/css">
    
    
    
    .lab_list li {
        margin:3px;
        padding:2px;
    }
    
    .lab_list li label {
        display:inline;
        float:none;
    }
    </style>
    <div class="center_content"> 
	    <div id="right_wrap">
		    <div id="right_content">
		      <h2>清空货品</h2>
		      <form name="categoryForm" method="post" action="{base_url('lab_goods/empty_goods')}" onsubmit="return validation(this);">
		       <div class="form">
		           <div class="form_row clearfix">
                       <label>试剂名称</label><input type="text" class="form_input" name="goods_name" value="{$smarty.post.goods_name}"/>&nbsp;<input type="submit" class="form_submit"  value="开始清空" />
                       {if $gobackUrl }<input type="hidden" name="gobackUrl" value="{$gobackUrl}"/><input type="button" class="form_submit" value="返回" onclick="location.href='{$gobackUrl}'" />{/if}
                       <span class="tip">可不输入，如果有输入，则只清空指定试剂名称的数据</span>
                   </div>
                   <div class="form_row clearfix">
                      <label></label>
                      <div class="rounded_box clearfix">
                      
	                      <ul class="lab_list clearfix">
	                          <li><label><input type="checkbox" value="checkall" name="checkall" {if $smarty.post.checkall}checked{/if}/>全选/取消</label></li>
		                      {foreach from=$labList['data'] item=item}
		                      <li><label><input type="checkbox" name="lab_id[]" value="{$item['id']}" {if $lab_checked[$item['id']]}checked{/if}/>{$item['address']|escape}</label></li>
		                      {/foreach}
	                      </ul>
                      </div>
                    </div>
		        </div>
		        </form>
		    </div><!-- end of right content-->
		</div><!-- end of right wrap -->
		<script>
		
		function validation(form){
            if($("input[name='lab_id[]']:checked").size() == 0){
                $.jBox.tip('请勾选实验室:', '提示');
                return false;
            }
            
            return true;
        }
		
		$(function(){
		      $("input[name=checkall]").bind("click",function(){
		          var checked = $(this).prop("checked");
		          $("input[name='lab_id[]']").prop("checked",checked);
		      });
		      
		      {if $message}
	              {if $success }
	              $.jBox.success('{$message}',"提示");
	              {else}
	              $.jBox.error('{$message}', '提示');
	              {/if}
              {/if}
		});
		
		</script>
		{include file="./side.tpl"}
{include file="common/lab_admin_footer.tpl"}