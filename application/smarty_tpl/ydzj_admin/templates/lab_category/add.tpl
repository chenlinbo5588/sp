{include file="common/main_header.tpl"}
    {include file="common/dhtml_tree.tpl"}
    {if $info['id']}
    <form name="categoryForm" method="post" action="{base_url('lab_category/edit')}" onsubmit="return validation(this);">
    {else}
    <form name="categoryForm" method="post" action="{base_url('lab_category/add')}" onsubmit="return validation(this);">
    {/if}
	   <input type="hidden" name="id" value="{$info['id']}"/>
	   <input type="hidden" name="pid" value="{$info['pid']}"/>
	   <div class="form">
	        <div class="form_row clearfix">
	          <label class="require"><em>*</em>类别名称:</label>
	          <input type="text" class="form_input" name="name" value="{$info['name']|escape}" placeholder="请输入类别名称" />
	          {form_error('name')}
	        </div>
	        <div class="form_row clearfix">
	          <label>{if $act == 'edit' }更改{/if}父类别:</label>
	          <div id="treeboxbox_tree1" class="rounded_box" style="height:400px;">
	              <div id="loading_img" class="loading_div" style="display:none;"></div>
	          </div>
	        </div>
	        <div class="form_row clearfix"><label></label>{form_error('pid')}</div>
	        
	        <div class="form_row paddingleft100">
	           <input type="submit" class="form_submit" name="submit" value="保存" />
	           {if $gobackUrl }<input type="hidden" name="gobackUrl" value="{$gobackUrl}"/><input type="button" class="form_submit" value="返回" onclick="location.href='{$gobackUrl}'" />{/if}
	        </div> 
	    </div>
    </form>
    
    <script>
	    var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
	    tree.setImagePath("/js/dhtmlxTree_v413_std/skins/web/imgs/dhxtree_web/");
	    
	    function tonclick(id){
	        if(id == 'root'){
	            $("input[name=pid]").val(0);
	        }else{
	            $("input[name=pid]").val(id);
	        }
	    };
	    
	    tree.enableHighlighting(true);
	    tree.enableSmartXMLParsing(true);
	    
	    tree.setOnClickHandler(tonclick);
	    
	    
	    function treeLoading(){
	        $("#loading_img").show();
	    }
	    
	    function treeLoaded(){
	        $("#loading_img").hide();
	    }
	    
	    tree.setOnLoadingStart(treeLoading);
	    tree.setOnLoadingEnd(treeLoaded); 
	    
	    tree.loadXML("{base_url('lab_category/getTreeXML')}",function(){
	        {if $act == 'add'}
	        {if $info['pid']}tree.selectItem({$info['pid']});{/if}
	        {elseif $act == 'edit'}
	        {if $info['id']}tree.selectItem({$info['id']});{/if}
	        {/if}
	    });
	    
	    function validation(form){
	        if($("input[name=name]").val() == ''){
	            $.jBox.tip('请输入类别名称:', '提示');
	            $("input[name=name]").focus();
	            return false;
	        }
	        
	        if($("input[name=pid]").val() == ''){
	            $.jBox.tip('请选择父级:', '提示');
	            return false;
	        }
	        
	        return true;
	    }
	
	    {if $message}
	      $(function(){
	          {if $success }
	          $.jBox.success('{$message}',"提示");
	          {else}
	          $.jBox.error('{$message}', '提示');
	          {/if}
	      });
	      {/if}
    </script>
{include file="common/main_footer.tpl"}