<input type="hidden" name="next_url" value=""/>
<input type="hidden" name="next_url" value=""/>
<div>
    <span class="pd5">发件人:{if $info['from_uid'] == 0}系统{else}{$info['from_username']}{/if}</span><span class="pd5">发件时间:{time_tran($info['gmt_create'])}</span>
    <span class="pd5">收件人:{$info['username']}</span>
</div>
<h2 class="pm_title pd5">标题:{$info['title']}</h2>
<div class="pm_content">{$info['content']}</div>
