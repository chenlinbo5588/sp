{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>清理缓存</h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  <form id="cache_form" method="post" action="{admin_site_url('cache/clear')}">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table nobdb">
      <tbody>
        <tr>
          <td colspan="2"><table class="table nomargin">
              <tbody>
                <tr>
                  <td class="required"><input id="cls_full" name="cls_full" value="1" type="checkbox"/>
                    &nbsp;
                    <label for="cls_full">全部</label></td>
                </tr>
                <tr class="noborder">
                  <td class="vatop rowform"><ul class="nofloat w830">
                      <li class="left w18pre">
                        <label>
                          <input type="checkbox" name="cache[]" value="goodsclass" >
                          &nbsp;商品分类</label>
                      </li>
                      <li class="left w18pre">
                        <label>
                          <input type="checkbox" name="cache[]" value="adv" >
                          &nbsp;广告缓存</label>
                      </li>          
                      <li class="left w18pre">
                        <label>
                          <input type="checkbox" name="cache[]" id="nav" value="nav" >
                          &nbsp;底部导航</label>
                      </li>
                      <li class="left w18pre">
                        <label>
                          <input type="checkbox" name="cache[]" id="index" value="index" >
                          &nbsp;首页</label>
                      </li>
                      <li class="left w18pre">
                        <label>
                          <input type="checkbox" name="cache[]" id="table" value="table" >
                          &nbsp;表结构</label>
                      </li>
                      <li class="left w18pre">
                        <label>
                          <input type="checkbox" name="cache[]" id="seo" value="seo" >
                          &nbsp;SEO</label>
                      </li>
                    </ul></td>
                </tr>
              </tbody>
            </table></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  
  <script>
//按钮先执行验证再提交表
$(function(){

    $('#cls_full').click(function(){
        $('input[name="cache[]"]').attr('checked',$(this).attr('checked') == 'checked');
    });
});
</script>
{include file="common/main_footer.tpl"}