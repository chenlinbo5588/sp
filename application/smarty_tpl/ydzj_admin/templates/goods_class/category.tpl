{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品分类</h3>
      <ul class="tab-base">
      	<li><a class="current"><span>管理</span></a></li>
      	<li><a href="{admin_site_url('goods_class/add')}"><span>新增</span></a></li>
      	<li><a href="{admin_site_url('goods_class/export')}"><span>导出</span></a></li>
      	<li><a href="{admin_site_url('goods_class/import')}"><span>导入</span></a></li>
      	<li><a href="{admin_site_url('goods_class/tag')}"><span>TAG管理</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
          <ul>
            <li>当添加商品时可选择商品分类，用户可根据分类查询商品列表</li>
            <li>点击分类名前“+”符号，显示当前分类的下级分类</li>
            {*<li><a>对分类作任何更改后，都需要到 设置 -> 清理缓存 清理商品分类，新的设置才会生效</a></li>*}
          </ul>
        </td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url('goods_class/category'),'id="searchForm"')}
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="table tb-type2" id="listtable">
      <thead>
        <tr class="thead">
          <th></th>
          <th>排序</th>
          <th>分类名称</th>
          <th>类型</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list item=item}
      	<tr class="hover edit" id="row{$item['gc_id']}">
          <td class="w48">
          	<img fieldid="{$item['gc_id']}" status="open" nc_type="flex" src="{resource_url('img/tv-expandable.gif')}">
          </td>
          <td class="w48 sort"><span class="editable ">{$item['gc_sort']}</span></td>
          <td class="w50pre name">
          	<span title="可编辑" class="editable ">{$item['gc_name']|escape}</span>
          	<a class="btn-add-nofloat marginleft" href="{admin_site_url('goods_class/add')}?gc_parent_id={$item['gc_id']}"><span>新增下级</span></a>
          </td>
          <td></td>
          <td class="w84"><a href="{admin_site_url('goods_class/edit')}?gc_id={$item['gc_id']}">编辑</a> | <a class="delete" href="javascript:void(0);" data-id="{$item['gc_id']}">删除</a></td>
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
  				url: '{admin_site_url('goods_class/delete')}?del_id='+id,
  				dataType:'json',
  				data :{ formhash : formhash },
  				success:function(json){
  					$("#row" + id).remove();
  					$(".row" + id).remove();
  					refreshFormHash(json.data);
  				},
  				error:function(){
  					alert("删除出错");
  				}
  			});
  				
  		}
  	});
  	
  	$("#listtable").delegate("img[nc_type='flex']", "click",function(){
		var status = $(this).attr('status');
		if(status == 'open'){
			var pr = $(this).parent('td').parent('tr');
			var id = $(this).attr('fieldid');
			var obj = $(this);
			$(this).attr('status','none');
		}
		
		//ajax
		$.ajax({
			url: '{admin_site_url('goods_class/category')}?gc_parent_id='+id,
			success: function(html){
				if($.trim(html) != ''){
					pr.after(html);
				}
				
				obj.attr('status','close');
				obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
			},
			error: function(){
				alert('获取信息失败');
			}
		});
		
		if(status == 'close'){
			$(".row"+$(this).attr('fieldid')).remove();
			$(this).attr('src',$(this).attr('src').replace("tv-collapsable","tv-expandable"));
			$(this).attr('status','open');
		}
	});
	
  });
  
  </script>
{include file="common/main_footer.tpl"}