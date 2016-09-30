{include file="common/main_header.tpl"}
{config_load file="goods.conf"}
	{include file="./sub_nav.tpl"}
	<div class="fixed-empty"></div>
    <div class="feedback">{$feedback}</div>
    {include file="common/dhtml_tree.tpl"}
    {if $info['id']}
		{form_open(site_url('goods_category/edit?id='|cat:$info['id']),'name="categoryForm"')}
	{else}
		{form_open(site_url('goods_category/add'),'name="categoryForm"')}
	{/if}
	   <input type="hidden" name="id" value="{$info['id']}"/>
	   <input type="hidden" name="pid" value="{$info['pid']}"/>
	   <table class="table tb-type2">
      	<tbody>
	      	<tr class="noborder">
	          <td colspan="2" class="required"><label class="validation">{#category_name#}:</label></td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" class="txt"></td>
	          <td class="vatop tips"><label class="errtip" id="error_name"></label></td>
	        </tr>
	   	</tbody>
	   </table>
	   <table class="autotable">
    	<tbody>
    	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation">{#pid#}:</label><label class="errtip" id="error_pid"></label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform">
          	<div id="treeboxbox_tree1" class="rounded_box" style="height:400px;">
          		<div id="loading_img" class="loading_div" style="display:none;"></div>
	        </div>
          </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </form>
    
    <script>
	    var tree=new dhtmlXTreeObject("treeboxbox_tree1","100%","100%",0);
	    tree.setImagePath("{$smarty.const.TREE_IMG_PATH}");
	    
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
	    
	    tree.loadXML("{site_url('goods_category/getTreeXML')}",function(){
	        {if $info['id']}
	        	tree.selectItem({$info['id']});
	        {else}
	        	{if $info['pid']}tree.selectItem({$info['pid']});{/if}
	        {/if}
	    });
	    
	    $(function(){
	    	{include file="common/form_ajax_submit.tpl"}
	    });
    </script>
{include file="common/main_footer.tpl"}