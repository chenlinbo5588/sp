<div class="page">
  {form_open(admin_site_url('member/pic_cut'),'id="form_cut"')}
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" id="x" name="x" value="{$smarty.get['x']}" />
    <input type="hidden" id="x1" name="x1" />
    <input type="hidden" id="y1" name="y1" />
    <input type="hidden" id="x2" name="x2" />
    <input type="hidden" id="y2" name="y2" />
    <input type="hidden" id="w" name="w" />
    <input type="hidden" id="h" name="h" />    
    <input type="hidden" id="url" name="url" value="{$smarty.get['url']}" />
    <input type="hidden" id="filename" name="filename" value="{$smarty.get['filename']}" />
    <div class="pic-cut-{$smarty.get['x']}{$smarty.get['type']}">
      <div class="work-title">工作区域</div>
      <div class="work-layer">
        <p><img id="nccropbox" src="{$smarty.get['url']}"/></p>
      </div>
      <div class="thumb-title">裁切预览</div>
      <div class="thumb-layer">
        <p><img id="preview" src="{$smarty.get['url']}"/></p>
      </div>
      <div class="cut-help">
        <h4>操作帮助</h4>
        <p>请在工作区域放大缩小及移动选取框，选择要裁剪的范围，裁切宽高比例固定；裁切后的效果为右侧预览图所显示；保存提交后生效。</p>
      </div>
      <div class="cut-btn"> <a href="JavaScript:void(0);" class="btn" id="pic_submit"><span>提交</span></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">

function showPreview(coords)
{
    if (parseInt(coords.w) > 0){
        var rx = {$smarty.get['x']} / coords.w;
        var ry = {$smarty.get['y']} / coords.h;
        $('#preview').css({
            width: Math.round(rx * {$image_width}) + 'px',
            height: Math.round(ry * {$image_height}) + 'px',
            marginLeft: '-' + Math.round(rx * coords.x) + 'px',
            marginTop: '-' + Math.round(ry * coords.y) + 'px'
        });
    }
    $('#x1').val(coords.x);
    $('#y1').val(coords.y);
    $('#x2').val(coords.x2);
    $('#y2').val(coords.y2);
    $('#w').val(coords.w);
    $('#h').val(coords.h);
}
function submitCoords(c){
    $('#pic_submit').click();
}
$(function(){
    $('.dialog_head').css('margin-top','0px');
    $('.page').css('padding-top','0px');
    $('.dialog_close_button').remove();
    $('#nccropbox').Jcrop({
      aspectRatio: {$smarty.get['ratio']},
      setSelect: [ 0, 0, {$smarty.get['x']}, {$smarty.get['y']} ],
      minSize:[50, 50],
      allowSelect:0,
      allowResize:{$smarty.get['resize']},
      onChange: showPreview,
      onSelect: showPreview,
      onDblClick:submitCoords
    });

    $('#pic_submit').click(function(){
        var d=$('#form_cut').serialize();
        $.post('{admin_site_url('member/pic_cut')}',d,function(data){
            call_back(data);
            //DialogManager.close('cutpic');
        },'json');
    });
});
</script>