{include file="common/header.tpl"}
<div class="handle_area">
	{if $mailed}
	<div class="row">
		<p>我们已经发送验证邮件到{$email}, 请登录邮箱进行激活</p>
	</di>
	{/if}

    {form_open(site_url('my/set_city'),"id='setCityForm'")}
    <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
    <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
    <div id="profile_city">
        <div class="row">
            <label class="side_lb" for="d1_sel">省：</label>
            <select name="d1" id="d1_sel" class="at_txt">
                <option value="">请选择</option>
                {foreach from=$d1 item=item}
                <option value="{$item['id']}" {if $profile['basic']['d1'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                {/foreach}
            </select>
        </div>
        <div class="form_error" id="d1_error"></div>
        <div class="row">
            <label class="side_lb" for="d2_sel">市：</label>
            <select name="d2" id="d2_sel" class="at_txt">
                <option value="">请选择</option>
                {foreach from=$d2 item=item}
                <option value="{$item['id']}" {if $profile['basic']['d2'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                {/foreach}
            </select>
        </div>
        <div class="form_error" id="d2_error"></div>
        <div class="row">
            <label class="side_lb" for="d3_sel">县：</label>
            <select name="d3" id="d3_sel" class="at_txt">
                <option value="">请选择</option>
                {foreach from=$d3 item=item}
                <option value="{$item['id']}" {if $profile['basic']['d3'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                {/foreach}
            </select>
        </div>
        <div class="form_error" id="d3_error"></div>
        <div class="row">
            <label class="side_lb" for="d4_sel">街道/镇：</label>
            <select name="d4" id="d4_sel" class="at_txt">
                <option value="">请选择</option>
                {foreach from=$d4 item=item}
                <option value="{$item['id']}" {if $profile['basic']['d4'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                {/foreach}
            </select>
        </div>
        <div class="form_error" id="d4_error"></div>
        <div class="row">
            <input type="button" name="submit" class="primaryBtn" value="保存"/>
        </div>
    </div>
    </form>
</div>

<script>
var cityUrl = "{site_url('district/index/')}";
$.loadingbar({ autoHide: true});

$(function(){
    $("input[name=submit]").bind("click",function(e){
        $.ajax({
	        type:"POST",
	        dataType: "json",
	        url:"{site_url('my/set_city')}",
	        data:$("#setCityForm").serialize(),
	        success:function(resp){
	           if(resp.message == '设置成功'){
	               alert(resp.message);
	               location.href = resp.data.url;
	           }else{
	               $("input[name=formhash]").val(resp.data.formhash);
	               $(".form_error").html('');
	               for(var err in resp.data.errormsg){
	                   $("#" + err + "_error").html(resp.data.errormsg[err]);
	               }
	           }
	        }
	    });
    });
    
});
</script>
<script src="{base_url('js/user/my.js')}" type="text/javascript"></script>

{include file="common/footer.tpl"}