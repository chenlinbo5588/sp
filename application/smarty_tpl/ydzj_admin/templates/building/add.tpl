{include file="common/main_header.tpl"}
<link rel="stylesheet" href="{resource_url('css/zq.css')}"/>
<style>
#map {
    width:100%;
    height:600px;
}
form label.error { display:block;}

</style>
  {form_open_multipart(admin_site_url('building/add'),'id="user_form"')}
  <ul class="edit_ul col3 clearfix" style="width:900px;">
        {foreach from=$fields key=key item=item}
        <li>
         <label class="side_lb{if $item['required']} required{/if}" for="{$key}">{if $item['required']}<em class="required">*</em>{/if}{$item['title']|escape}{$item['tip']}:</label>
            {if $item['type'] == 'select'}
            <select name="{$key}" id="{$key}">
            {foreach from=$item['dataSource'] key=dk item=dv}
            <option value="{$dk}" {set_select($key,$dk)}>{$dv}</option>
            {/foreach}
            </select>
            {elseif $item['type'] == 'textarea'}
            <textarea name="{$key}" id="{$key}">{set_value($key)}</textarea>
            {else}
            <input type="text" value="{set_value($key)}" name="{$key}" id="{$key}" class="txt" {if $item['readonly']}readonly{/if}>
            {/if}
            {form_error($key)}
        </li>
        {/foreach}
        <li><input type="submit" name="submit" value="保存" class="msbtn"/><li>
   </ul>
  </form>
  <div id="map">
    <p class="tip_warning">请点击图上点标注</p>
    {include file="./simple_map.tpl"}
  </div>
</div>
<script>

var point_x = "{$smarty.post['x']}";
var point_y = "{$smarty.post['y']}";

function setPointXY(e){
    $("input[name=x]").val(e.mapPoint.x);
    $("input[name=y]").val(e.mapPoint.y);
}

function createPoint(){
    if(point_x && point_y){
        return { x: point_x , y : point_y };
    }
}
</script>
{include file="common/main_footer.tpl"}