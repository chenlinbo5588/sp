{include file="common/my_header.tpl"}
  {config_load file="lab_goods.conf"}
  <div class="w-tixing clearfix"><b>温馨提醒：</b>
    <p>1、{#goods_name#}留空表示清空选中的实验室的所有货品,填写{#goods_name#}则只清空指定的数据。</p>
    <p>2、勾选实验室,则只清空勾选的实验室</p>
  </div>
  <div id="handleDiv">
	  <form name="goodsForm" method="post" action="{site_url($uri_string)}" onsubmit="return validation(this);">
	  <input type="hidden" name="lab_id" value=""/>
	  <table class="fulltable">
	      <tbody>
	        <tr class="noborder">
	          <td class="required w120"><label class="validation">{#goods_name#}:</label></td>
	          <td class="vatop rowform">
	              <input type="text" class="txt" name="goods_name"  value="{$smarty.post.goods_name}"/>
	              <input type="submit" id="begin_empty" name="tijiao" value="确定" class="master_btn"/>
	          </td>
	        </tr>
	        <tr class="noborder">
	          <td class="required"><label class="validation">实验室:</label></td>
	          <td>
	    		<div>
	    			<img src="{resource_url($smarty.const.TREE_IMG_PATH|cat:'iconCheckAll.gif')}"/><span class="blue">表示已选中</span>
	    			<img src="{resource_url($smarty.const.TREE_IMG_PATH|cat:'iconUncheckAll.gif')}"/><span class="red">表示未选中</span>
	    		</div>
	    		<div id="treeboxbox_tree1" class="treebox rounded_box" style="height:400px;">
	               <div id="loading_img" class="loading_div" style="display:none;"></div>
	          	</div>
	    	  </td>
	        </tr>
	       </tbody>
	       </table>
	    </form>
    </div>
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
	    
	    setTimeout(function(){
	    	tree.loadXML("{site_url('lab/getTreeXML')}",function(){
		    	{if $lab_id}
		    	{foreach from=$lab_id item=item}
		    	tree.setCheck({$item}, true);
		    	{/foreach} 
		    	{/if}
	        });
	    },500);
	    
        
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
	        	showToast('error','请先勾选');
	            return false;
	        }
	        
	        return true;
	    }
	    
	    $(function(){
            $("form").each(function(){
                var name = $(this).prop("name");
                formLock[name] = false;
            });
            
            $.loadingbar({ urls: [ new RegExp($("form[name=goodsForm]").attr('action')) ], templateData:{ message:"努力加载中..." } ,container: "#handleDiv" });
            bindAjaxSubmit('form');
        });
	    
	</script>
{include file="common/my_footer.tpl"}