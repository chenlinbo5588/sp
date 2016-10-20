{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    {if $infreezen}
    <div class="panel pd20 warnbg">
        <span>当前库存暂不可更新，冻结时间未到，还剩{$leftseconds}秒</span>
    </div>
    {else}
    {include file="./import.tpl"}
    {/if}
{include file="common/my_footer.tpl"}