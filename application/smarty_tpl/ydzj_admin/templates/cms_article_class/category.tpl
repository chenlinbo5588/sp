{include file="common/main_header.tpl"}
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
          <ul>
            <li>当添加文章时可选择文章分类，用户可根据分类查询文章列表</li>
            <li>点击分类名前“+”符号，显示当前分类的下级分类</li>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url('cms_article_class/category'),'id="searchForm"')}
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
          <td class="w48 sort"><span class="editable ">{$item['ac_sort']}</span></td>
          <td class="name">
          	<span title="可编辑" class="editable ">{$item['name']|escape}</span>
          	<a class="btn-add-nofloat marginleft" href="{admin_site_url('cms_article_class/add')}?pid={$item['id']}"><span>新增下级</span></a>
          </td>
          <td></td>
          <td class="w84"><a href="{admin_site_url('cms_article_class/edit')}?id={$item['id']}">编辑</a> | <a class="delete" href="javascript:void(0);" data-id="{$item['id']}">删除</a></td>
        </tr>
        {/foreach}
      </tbody>
     </table>
  </form>
  <script type="text/javascript">
  $(function(){
  	//列表下拉
  	$("#listtable").delegate("a.delete","click",function(){
  		if(confirm('删除该分类将会同时删除该分类的所有下级分类，您确定要删除吗')){
  			var id = $(this).attr("data-id");
  			$.ajax({
  				type:"POST",
  				url: '{admin_site_url('cms_article_class/delete')}',
  				dataType:'json',
  				data :{ formhash : formhash , id : id },
  				success:function(json){
  					$("#row" + id).remove();
  					$(".row" + id).remove();
  					refreshFormHash(json.data);
  				},
  				error:function(){
  					alert("删除出错");
  				}
  			})
  				
  		}
  	})
  	
  	$("#listtable").delegate("img[nc_type='flex']", "click",function(){
  		var obj = $(this);
		var status = obj.attr('status');
		if(status == 'open'){
			var pr = obj.parent('td').parent('tr');
			var id = obj.attr('fieldid');
			obj.attr('status','none');
		
			$.ajax({
				url: '{admin_site_url('cms_article_class/category')}?pid='+id,
				success: function(html){
					if($.trim(html) != ''){
						$(html).insertAfter(pr);
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
	})
  })
  </script>
{include file="common/main_footer.tpl"}