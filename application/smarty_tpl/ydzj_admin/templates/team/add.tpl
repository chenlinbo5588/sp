{include file="common/main_header.tpl"}
{config_load file="team.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('team')}" ><span>{#manage#}</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>{#add#}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {form_open_multipart($formUrl,'id="team_form"')}
    <table class="table tb-type2">
      <colgroup>
        <col width="10%"/>
      </colgroup>
      <tbody>
        <tr class="noborder">
            <td class="vatop"><label class="validation" for="member_username">{#team_category#}:</label></td>
            <td class="vatop rowform">
                <select name="category_id">
                    {foreach from=$sportsCategoryList item=item}
                    <option value="{$item['id']}">{$item['name']}</option>
                    {/foreach}
                </select>
                {form_error('category_id')}
            </td>
        </tr>
        {*
        <tr class="noborder">
          <td class="vatop">
            <label class="validation">队伍所在地:</label>
          </td>
          <td class="vatop">
            <div class="cityGroupWrap">
                <select name="d1" class="class-select citySelect">
                    <option value="">{#choose#}</option>
                    {foreach from=$ds['d1'] item=item}
                    <option value="{$item['id']}" {if $smarty.post['d1'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                    {/foreach}
                </select>
                <select name="d2" class="class-select citySelect">
                    <option value="">{#choose#}</option>
                    {foreach from=$ds['d2'] item=item}
                    <option value="{$item['id']}" {if $smarty.post['d2'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                    {/foreach}
                </select>
                <select name="d3" class="class-select citySelect">
                    <option value="">{#choose#}</option>
                    {foreach from=$ds['d3'] item=item}
                    <option value="{$item['id']}" {if $smarty.post['d3'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                    {/foreach}
                </select>
                <select name="d4" class="class-select citySelect">
                    <option value="">{#choose#}</option>
                    {foreach from=$ds['d4'] item=item}
                    <option value="{$item['id']}" {if $smarty.post['d4'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                    {/foreach}
                </select>
                {form_error('d1')}
                {form_error('d2')}
                {form_error('d3')}
                {form_error('d4')}
            </div>
          </td>
        </tr>
        *}
        <tr class="noborder">
          <td class="vatop">
            <label class="validation" for="team_title">{#team_name#}:</label>
          </td>
          <td class="vatop rowform">
            <input type="text" id="team_title" value="{$info['title']}" name="title" class="txt"/>
            {form_error('title')}
          </td>
        </tr>
        <tr class="noborder">
          <td class="vatop">
            <label class="validation" for="leader">{#leader_account#}:</label>
          </td>
          <td class="vatop rowform">
            <input type="hidden"  id="leader" value="1" name="leader" class="txt"/>
            <input type="text"  id="leader_account" value="{$info['leader_account']}" name="leader_account" class="txt"/>
            {form_error('leader_account')}
          </td>
        </tr>
        <tr class="noborder">
          <td class="vatop">
            <label class="validation" for="leader">{#join_type#}:</label>
          </td>
          <td class="vatop rowform">
            <select name="joined_type">
                <option value="1">邀请加入</option>
            </select>
            {form_error('joined_type')}
          </td>
        </tr>
        <tr class="noborder">
          <td class="vatop">
            <label class="validation" for="accept_game">{#accept_game#}:</label>
          </td>
          <td class="vatop rowform">
            <select name="accept_game">
                <option value="1" {if $info['accept_game'] == 1}selected{/if}>可接受比赛预约</option>
                <option value="0">休战</option>
            </select>
            {form_error('accept_game')}
          </td>
        </tr>
        <tr>
          <td class="required"><label class="validation">队伍合影(支持格式jpg):</label></td>
          <td class="vatop">
            <input type="hidden" name="aid" value="{$info['aid']}"/>
            <input type="hidden" name="avatar" value="{$info['avatar']}"/>
            <input type="file" name="team_avatar"/>
            {$logo_error}
        </tr>
        <tr>
            <td></td>
            <td>
                {if $info['avatar']}
		        <img style="width:300px" src="{resource_url($info['avatar'])}"/>
		        {/if}
            </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td>&nbsp;</td>
          <td><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}