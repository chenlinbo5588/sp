{include file="common/main_header.tpl"}
  {config_load file="goods.conf"}
  {include file="./goods_common.tpl"}
  <div class="feedback">{$feedback}</div>
  <div class="fixed-empty"></div>
  <form name="categoryForm" method="post" action="{admin_site_url('goods/empty_goods')}" onsubmit="return validation(this);">
  <input type="hidden" name="lab_id" value=""/>
  <table class="autotable">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#goods_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              <input type="text" class="txt" name="goods_name"  value="{$smarty.post.goods_name}"/>
              <input type="submit" id="begin_empty" name="submit" value="确定" class="msbtn"/>
          </td>
          <td class="vatop tips"><span class="warning">留空表示清空选中的实验室的所有货品,填写{#goods_name#}则只清空指定的数据</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">实验室:</label></td>
        </tr>
        <tr class="noborder">
        	<td colspan="2">
        		<div>
        			<img src="{resource_url($smarty.const.TREE_IMG_PATH|cat:'iconCheckAll.gif')}"/><span class="blue">表示已选中</span>
        			<img src="{resource_url($smarty.const.TREE_IMG_PATH|cat:'iconUncheckAll.gif')}"/><span class="red">表示未选中</span>
        		</div>
        		<div id="treeboxbox_tree1" class="rounded_box" style="height:400px;">
	               <div id="loading_img" class="loading_div" style="display:none;"></div>
	          	</div>
        	</td>
        </tr>
       </tbody>
    </form>
    <div id="dialog-confirm" title="提示" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span><span>请勾选实验室</span><span class="hightlight">3秒后自动关闭</span></p></div>
    {include file="common/jquery_ui.tpl"}
    {include file="common/dhtml_tree.tpl"}
    <script>
     	var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
	    tree.setImagePath("{$smarty.const.TREE_IMG_PATH}");
	    tree.enableHighlighting(true);
	    tree.enableSmartXMLParsing(true);
	    tree.enableCheckBoxes(true, true);
		//tree.enableThreeStateCheckboxes(true);
		tree.enableSmartCheckboxes(true);
	    tree.setOnCheckHandler(toncheck);
	    
	    function toncheck(id,state){
		    tree.setSubChecked(id, state);
		    setLabIds();
		};
	    
	    
	    tree.loadXML("{admin_site_url('lab/getTreeXML?uid='|cat:$admin_profile['basic']['id'])}",function(){
	    	{if $lab_id}
	    	{foreach from=$lab_id item=item}
	    	tree.setCheck({$item}, true);
	    	{/foreach} 
	    	{/if}
        });
        
        function setLabIds(){
		    var allchecked = tree.getAllChecked();
		    var allPartiallyChecked = tree.getAllPartiallyChecked();
		    
		    if(allchecked && allPartiallyChecked){
		        $("input[name=lab_id]").val(allchecked + "," + allPartiallyChecked);
		    }else if(allchecked){
		        $("input[name=lab_id]").val(allchecked);
		    }else if(allPartiallyChecked){
		        $("input[name=lab_id]").val(allPartiallyChecked);
		    }
		}
		
		function validation(form){
			setLabIds();
			
	        if($("input[name='lab_id']").val() == ''){
	        	$("#dialog-confirm").dialog({
				      autoOpen: false,
				      height: 80,
				      width: '20%',
				      modal: false,
				      resizable: false
				      
	        	}).dialog( "open" );
	        	
	        	setTimeout(function(){
	        		$("#dialog-confirm").dialog( "close" );
	        	},2000);
	                	
	            return false;
	        }
	        
	        return true;
	    }
	
	</script>
{include file="common/main_footer.tpl"}