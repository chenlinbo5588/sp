{include file="common/main_header.tpl"}
  {config_load file="basic_data.conf"}
  {include file="common/sub_nav.tpl"}
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
          <ul>
            <li>当添加家政从业人员时可选择关联的基础数据，用户可根据分类查询家政从业人员列表</li>
            <li>点击分类名前“+”符号，显示当前分类的下级分类</li>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url($moduleClassName|cat:'/category'),'id="searchForm"')}
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="table tb-type2" id="listtable">
      <thead>
        <tr class="thead">
          <th></th>
          <th>排序</th>
          <th>分类名称</th>
          <th></th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td class="w48">
          	<img fieldid="{$item['id']}" status="open" nc_type="flex" src="{resource_url('img/tv-expandable.gif')}">
          </td>
          <td class="w48 sort"><span class="editable" data-id="{$item['id']}" data-fieldname="displayorder">{$item['displayorder']}</span></td>
          <td class="w50pre name">
          	<span title="可编辑" class="editable" data-id="{$item['id']}" data-fieldname="show_name">{$item['show_name']|escape}</span>
          	<a class="btn-add-nofloat marginleft" href="{admin_site_url($moduleClassName|cat:'/add')}?pid={$item['id']}"><span>新增下级</span></a>
          </td>
          <td></td>
          <td class="w84"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a> | <a class="delete" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" href="javascript:void(0);" data-id="{$item['id']}">删除</a></td>
        </tr>
        {/foreach}
      </tbody>
     </table>
  </form>
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
  <script type="text/javascript">
  $(function(){
  
  	bindDeleteEvent(function(ids,json){
        showToast('success',json.message);
        
        for(var i = 0; i < ids.length; i++){
            $("#row" + ids[i]).remove();
            $(".row" + ids[i]).remove();
        }
        refreshFormHash(json.data);
    },function(){
        showToast('error','删除错误');
    });
  	
  	$("#listtable").delegate("img[nc_type='flex']", "click",function(){
  		var obj = $(this);
		var status = obj.attr('status');
		
		
		if(status == 'open'){
			var pr = obj.parent('td').parent('tr');
			var id = obj.attr('fieldid');
			obj.attr('status','close');
		
			//ajax
			$.ajax({
				url: '{admin_site_url($moduleClassName|cat:'/category')}?pid='+id,
				success: function(html){
					if($.trim(html) != ''){
						$("span.editable").unbind( "click" );
						
						$(html).insertAfter(pr);
						
						bindFn();
					}else{
						showToast('warning','已无更多数据' ,{ hideAfter : 2000 } );
					}
					
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
				},
				error: function(){
					alert('获取信息失败');
				}
			});
		} else if(status == 'close'){
			$(".row"+obj.attr('fieldid')).remove();
			obj.attr('src',obj.attr('src').replace("tv-collapsable","tv-expandable"));
			obj.attr('status','open');
		}
	});
    
    var bindFn = function(){
    	$("span.editable").inline_edit({ 
	    	url: "{admin_site_url($moduleClassName|cat:'/inline_edit')}",
	    	clickNameSpace:'inlineEdit'
	    });
    }
    
    bindFn();
	
  });
  
  </script>
{include file="common/main_footer.tpl"}