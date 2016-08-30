{include file="common/main_header.tpl"}
{config_load file="stadium.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}{#manage#}</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('stadium/index')}" class="current"><span>{#manage#}</span></a></li>
        <li><a href="{admin_site_url('stadium/add')}" ><span>{#add#}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch" action="{admin_site_url('stadium/index')}">
    <input type="hidden" name="page" value=""/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
            <td colspan="5">
                <div class="cityGroupWrap">
                	<label>选择地区</label>
                    <select name="d1" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d1'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d1'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d2" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d2'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d2'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d3" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d3'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d3'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                    <select name="d4" class="citySelect">
                        <option value="">{#choose#}</option>
                        {foreach from=$ds['d4'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d4'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
                    </select>
                </div>
            </td>
        </tr>
        <tr>
          <td colspan="2"><label>{#stadium_name#}<input type="text" value="{$smarty.get['name']}" name="name" class="txt"></label></td>
         <td><select name="create_time">
              <option  value="">{#create_time#}</option>
              {foreach from=$search_map['create_time'] key=key item=item}
              <option  value="{$key}" {if $smarty.get['create_time'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
         </td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
   </form>
   <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2">{#stadium_info#}</th>
          <th>{#address#}以及{#contact#}</th>
          <th>{#booking_enable#}</th>
          <th>状态</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover member">
          <td class="w24"><input name="id[]" group="chkVal" type="checkbox" value="{$item['id']}"></td>
          <td class="w120 picture">
            <div class=""><span class="thumb"><i></i><img src="{resource_url($item['avatar_m'])}"  data-avatar="{$item['avatar_b']}" /></span></div></td>
          <td>
            <p class="name"><strong>{#stadium_name#}:&nbsp;{$item['name']|escape}</strong></p>
            <p><strong>{#category_name#}:&nbsp;</strong><span>{$item['category_name']|escape}</span></p>
            <p><strong>{#ground_type#}:&nbsp;</strong><span>{$item['ground_type']|escape}</span></p>
            <p><strong>{#charge_type#}:&nbsp;</strong><span>{$item['charge_type']|escape}</span></p>
            <p><strong>{#open_type#}:&nbsp;</strong><span>{$item['open_type']|escape}</span></p>
            <p><strong>{#owner_type#}:&nbsp;</strong><span>{$item['owner_type']|escape}</span></p>
            <p><strong>{#support_sports#}:&nbsp;</strong><span>{$item['support_sports']|escape}</span></p>
            <p class="smallfont">{#create_time#}:&nbsp;{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</p>
          </td>
          <td>
          	<div>{#contact#}:{$item['contact']|escape}</div>
          	<div>{#mobile#}:{$item['mobile']|escape}</div>
          	<div>{#mobile2#}:{$item['mobile2']|escape}</div>
          	<div>{#tel#}:{$item['tel']|escape}</div>
          	<div>{#address#}:{$item['province']}{$item['city']}{$item['district']}{$item['street']}{$item['street_number']}</div>
          </td>
          <td>{if $item['booking_enable'] == 'Y'}是{else}否{/if}</td>
          <td>{if $item['status'] == 0}未审核{elseif $item['status'] == 1}审核通过{elseif $item['status'] == -1}审核未通过{/if}</td>
          <td><a href="{admin_site_url('stadium/edit')}?id={$item['id']}">编辑</a> | <a href="{admin_site_url('notify/stadium')}?id={$item['id']}">通知</a></td>
        </tr>
      {/foreach}
      </tbody>
      <tfoot class="tfoot">
        <tr>
          <td colspan="7">
            {include file="common/pagination.tpl"}
            <label><input type="checkbox" class="checkall" name="chkVal">全选</label>
            <input type="button" value="审核通过" class="btn" name="verfiy"/>
            <input type="button" value="审核失败" class="btn"  name="verfiy"/>
        </tr>
      </tfoot>
    </table>
<script>
$(function(){
    $("input[type=submit]").click(function(){
        $("#formSearch input[name=page]").val(1);
    });
});
</script>
{include file="common/main_footer.tpl"}