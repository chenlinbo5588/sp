{include file="common/header.tpl"}


<section>
    {form_open(site_url('my/set_city'))}
    <div id="profile_city">
        <h2>设置地区</h2>
        {form_error('category_id')}
        <div class="row">
            <label>省：<select name="category_id">
                {foreach from=$sportsCategoryList item=item}
                <option value="{$item['id']}" {set_select('category_id',$item['id'])}>{$item['name']}</option>
                {/foreach}
            </select><label>
        </div>
        {form_error('title')}
        <div class="row">
            <label>是：<input type="text" name="title" value="{set_value('title')}" placeholder="请输入队伍名称,最多20个汉字"/></label>
        </div>
        <div class="row">
            <input type="submit" name="submit" class="primaryBtn" value="保存"/>
        </div>
    </div>
    </form>
</section>
{include file="common/footer.tpl"}