{include file="common/header.tpl"}


<div class="handle_area">
	{if $mailed}
	<div class="row">
		<p>我们已经发送验证邮件到您的 <a href="#"/>{$email}</a> , 点击登陆有限进行邮箱验证</p>
	</di>
	{/if}

    {form_open(site_url('my/set_city'))}
    <div id="profile_city">
        {form_error('category_id')}
        <div class="row">
            <label class="side_lb" for="d1_sel">省：</label>
            <select name="d1" id="d1_sel" class="at_txt">
                <option value="">请选择</option>
                {foreach from=$d1 item=item}
                <option value="{$item['id']}" {set_select('d1',$item['id'])}>{$item['name']}</option>
                {/foreach}
            </select>
        </div>
        <div class="row">
            <label class="side_lb" for="d2_sel">市：</label>
            <select name="d2" id="d2_sel" class="at_txt">
                <option value="">请选择</option>
            </select>
        </div>
        <div class="row">
            <label class="side_lb" for="d3_sel">镇：</label>
            <select name="d3" id="d3_sel" class="at_txt">
                <option value="">请选择</option>
            </select>
        </div>
        <div class="row">
            <label class="side_lb" for="d4_sel">街道/乡：</label>
            <select name="d4" id="d4_sel" class="at_txt">
                <option value="">请选择</option>
            </select>
        </div>
        <div class="row">
            <input type="submit" name="submit" class="primaryBtn" value="保存"/>
        </div>
    </div>
    </form>
</div>

<script>
var cityUrl = "{site_url('district/index/')}";
</script>
<script src="{base_url('js/my.js')}" type="text/javascript"></script>
{include file="common/footer.tpl"}