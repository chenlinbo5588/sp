{include file="common/header.tpl"}
{$feedback}
<div class="handle_area">
    {form_open(site_url('team/order_game/'|cat:$teamid),"id='orderGameForm'")}
    <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
    <div>
        <div class="row" style="position:relative;">
            <label class="side_lb" for="avatar_txt"><em>*</em>球队合影：</label>
            <input type="file" class="at_txt" id="avatar_txt" name="avatar" value=""/>
        </div>
        <div class="row">
            <input type="submit" name="submit" class="primaryBtn" value="保存"/>
        </div>
    </div>
    </form>
</div>

<script>


</script>
{include file="common/footer.tpl"}