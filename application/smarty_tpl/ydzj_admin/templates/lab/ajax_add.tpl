      {if $action == 'add'}
      <form name="categoryForm" method="post" action="{base_url('lab_admin/add')}">
      {elseif $action = 'edit'}
      <form name="categoryForm" method="post" action="{base_url('lab_admin/edit')}">
      <input type="hidden" name="id" value="{$info['id']}"/>
      {/if}
       <div class="form">
            <div class="form_row clearfix">
              <label>类别名称:</label>
              <input type="text" class="form_input" name="name" value="{$info['name']|escape}" placeholder="请输入类别名称" />
              <span class="repuired"></span>
              {form_error('name')}
            </div>
            <div class="form_row clearfix">
              <label>父类别:</label>
              <div id="treeboxbox_tree2" class="rounded_box"></div>
              {*
              <select class="form_select" name="pid">
                  <option value="0">无</option>
                  {foreach from=$categoryList item=item key=key}
                  <option value="{$key}" {if $key == $info['pid']}selected{/if}>{$item['sep']}{$item['name']|escape}</option>
                  {/foreach}
              </select>
              *}
              <span class="repuired"></span>
              {form_error('pid')}
            </div>
            
            <div class="form_row">
               <input type="submit" class="form_submit marginleft100" value="保存" />
            </div> 
        </div>
        </form>
		<script>
            var tree=new dhtmlXTreeObject("treeboxbox_tree2","100%","100%",0);
            tree.setImagePath("/js/dhtmlxTree_v413_std/skins/web/imgs/dhxtree_web/");
            
            tree.loadXML("{base_url('lab_category/getTreeXML')}",function(){
                
            });
                
		  $(function(){
		      {if $message}
			      {if $success }
			      $.jBox.success('{$message}',"提示");
			      {else}
			      $.jBox.error('{$message}', '提示');
			      {/if}
		      {/if}
		  });
		  
		</script>
