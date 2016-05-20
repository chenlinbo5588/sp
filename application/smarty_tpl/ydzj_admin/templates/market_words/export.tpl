{include file="common/main_header.tpl"}
{config_load file="words.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	{if isset($permission['admin/market_words/index'])}<li><a href="{admin_site_url('market_words/index')}"><span>管理</span></a></li>{/if}
      	{if isset($permission['admin/market_words/add'])}<li><a href="{admin_site_url('market_words/add')}"><span>新增</span></a></li>{/if}
      	{if isset($permission['admin/market_words/import'])}<li><a href="{admin_site_url('market_words/import')}"><span>导入</span></a></li>{/if}
      	{if isset($permission['admin/market_words/export'])}<li><a class="current"><span>导出</span></a></li>{/if}
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
            <li>导出内容为关键词.csv文件</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url('market_words/export'),'id="market_words_form"')}
    <input type="hidden" name="if_convert" value="1" />
    <table class="table tb-type2">
    <thead>
        <tr class="thead">
          <th>导出您的关键词数据?</th>
      </tr></thead>
      <tfoot><tr class="tfoot">
        <td><input type="submit" name="submit" value="开始导出" class="msbtn"/></td>
      </tr></tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}