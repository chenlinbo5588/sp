{include file="common/header.tpl"}
{form_open($formUrl,'id="swictCityForm"')}
<input type="hidden" name="cityid" value="{$currentCity['id']}"/>
<div class="cityList">
    {if $currentCity['id'] != 0}
    <div class="row goback"><a href="{site_url($cityUrl|cat:$currentCity['upid'])}">返回上一级</a></div>
    {/if}
    {foreach from=$cityList item=item}
    <div class="row {if $item['id'] == $currentCity['id']}current{/if}">
        <a href="{site_url($cityUrl|cat:$item['id'])}">{$item['name']}</a>
    </div>
    {/foreach}
</div>
{*
<div class="row" id="submitFixedWrap" >
    <div class="row col">
        <input type="submit" class="master_btn" name="submit" value="确定" />
    </div>
</div>
*}
</form>
{include file="common/footer.tpl"}