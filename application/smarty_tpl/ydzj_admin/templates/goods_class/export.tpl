{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品分类</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('goods_class/category')}"><span>管理</span></a></li>
      	<li><a href="{admin_site_url('goods_class/add')}"><span>新增</span></a></li>
      	<li><a class="current"><span>导出</span></a></li>
      	<li><a href="{admin_site_url('goods_class/import')}"><span>导入</span></a></li>
      	<li><a href="{admin_site_url('goods_class/tag')}"><span>TAG管理</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>导出内容为商品分类信息的.csv文件</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url('goods_class/export'),'id="goods_class_form"')}
    <input type="hidden" name="if_convert" value="1" />
    <table class="table tb-type2">
    <thead>
        <tr class="thead">
          <th>导出您的商品分类数据?</th>
      </tr></thead>
      <tfoot><tr class="tfoot">
        <td><input type="submit" name="submit" value="保存" class="msbtn"/></td>
      </tr></tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}