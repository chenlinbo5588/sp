{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>导航管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=cms_navigation&amp;op=cms_navigation_list" class="current"><span>列表</span></a></li>
        <li><a href="index.php?act=cms_navigation&amp;op=cms_navigation_add"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <!-- 操作说明 -->
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd" style="background: rgb(255, 255, 255);">
        <th colspan="12" class="nobg"> <div class="title">
            <h5>操作提示</h5>
            <span class="arrow"></span> </div>
        </th>
      </tr>
      <tr class="odd" style="background: rgb(255, 255, 255);">
        <td><ul>
            <li>通过修改排序数字可以控制前台显示顺序，数字越小越靠前</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url('goods/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <input id="navigation_id" name="navigation_id" type="hidden">
    <table class="table tb-type2">
      <thead>
        <tr class="space">
          <th colspan="15" class="nobg">列表</th>
        </tr>
        <tr class="thead">
          <th></th>
          <th class="align-left">排序</th>
          <th class="align-left">导航名称</th>
          <th class="align-left">导航链接</th>
          <th class="align-left">新窗口打开</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
                        <tr class="hover edit">
          <td class="w48"><input type="checkbox" value="1" class="checkitem"></td>
          <td class="w48 sort"><span nc_type="navigation_sort" column_id="1" title="可编辑" class="editable ">255</span>
          </td><td class="name"><span nc_type="navigation_title" column_id="1" title="可编辑" class="editable ">商城</span></td>
          <td class="name"><span nc_type="navigation_link" column_id="1" title="可编辑" class="editable ">http://www.b2b2c.com/</span></td>
          <td class="yes-onoff"><a href="JavaScript:void(0);" class=" enabled" ajax_branch="navigation_open_type" nc_type="inline_edit" fieldname="navigation_open_type" fieldid="1" fieldvalue="1" title=""><img src="http://www.b2b2c.com/admin/templates/default/images/transparent.gif"></a></td>
          <td class="w72 align-center"><a href="javascript:submit_delete(1)">删除</a></td>
        </tr>
                <tr class="hover edit">
          <td class="w48"><input type="checkbox" value="2" class="checkitem"></td>
          <td class="w48 sort"><span nc_type="navigation_sort" column_id="2" title="可编辑" class="editable ">255</span>
          </td><td class="name"><span nc_type="navigation_title" column_id="2" title="可编辑" class="editable ">圈子</span></td>
          <td class="name"><span nc_type="navigation_link" column_id="2" title="可编辑" class="editable ">http://www.b2b2c.com/circle</span></td>
          <td class="yes-onoff"><a href="JavaScript:void(0);" class=" disabled" ajax_branch="navigation_open_type" nc_type="inline_edit" fieldname="navigation_open_type" fieldid="2" fieldvalue="0" title=""><img src="http://www.b2b2c.com/admin/templates/default/images/transparent.gif"></a></td>
          <td class="w72 align-center"><a href="javascript:submit_delete(2)">删除</a></td>
        </tr>
                <tr class="hover edit">
          <td class="w48"><input type="checkbox" value="3" class="checkitem"></td>
          <td class="w48 sort"><span nc_type="navigation_sort" column_id="3" title="可编辑" class="editable ">255</span>
          </td><td class="name"><span nc_type="navigation_title" column_id="3" title="可编辑" class="editable ">微商城</span></td>
          <td class="name"><span nc_type="navigation_link" column_id="3" title="可编辑" class="editable ">http://www.b2b2c.com/microshop</span></td>
          <td class="yes-onoff"><a href="JavaScript:void(0);" class=" disabled" ajax_branch="navigation_open_type" nc_type="inline_edit" fieldname="navigation_open_type" fieldid="3" fieldvalue="0" title=""><img src="http://www.b2b2c.com/admin/templates/default/images/transparent.gif"></a></td>
          <td class="w72 align-center"><a href="javascript:submit_delete(3)">删除</a></td>
        </tr>
                <tr class="hover edit">
          <td class="w48"><input type="checkbox" value="4" class="checkitem"></td>
          <td class="w48 sort"><span nc_type="navigation_sort" column_id="4" title="可编辑" class="editable ">255</span>
          </td><td class="name"><span nc_type="navigation_title" column_id="4" title="可编辑" class="editable ">品牌</span></td>
          <td class="name"><span nc_type="navigation_link" column_id="4" title="可编辑" class="editable ">http://www.b2b2c.com/shop/index.php?act=brand</span></td>
          <td class="yes-onoff"><a href="JavaScript:void(0);" class=" enabled" ajax_branch="navigation_open_type" nc_type="inline_edit" fieldname="navigation_open_type" fieldid="4" fieldvalue="1" title=""><img src="http://www.b2b2c.com/admin/templates/default/images/transparent.gif"></a></td>
          <td class="w72 align-center"><a href="javascript:submit_delete(4)">删除</a></td>
        </tr>
                      </tbody>
            <tfoot>
        <tr class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkall_1"></td>
          <td id="batchAction" colspan="15"><span class="all_checkbox">
            <label for="checkall_1">全选</label>
            </span>&nbsp;&nbsp; <a href="javascript:void(0)" class="btn" onclick="submit_delete_batch();"><span>删除</span></a>
            <div class="pagination"></div>
        </td></tr>
      </tfoot>
          </table>
  </form>


  

{include file="common/main_footer.tpl"}