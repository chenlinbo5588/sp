{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    {if $infreezen}
    <div class="panel pd20 warnbg">
        <span>发布冻结时间内，还剩{$leftseconds}秒</span>
    </div>
    {else}
    {include file="./pub_tip.tpl"}
    <div class="basic-information-list">
      {$stepHTML}
      <div class="hp-import-box">
        {form_open_multipart(site_url('hp/import'),"id='hpForm'")}
        <input id="file_upload" type="file" name="Filedata"/>
        <input type="submit" class="master_btn" name="tijiao" value="开始导入"/>
        </form>
      </div>
    </div>
    {/if}
{include file="common/my_footer.tpl"}
        
        
