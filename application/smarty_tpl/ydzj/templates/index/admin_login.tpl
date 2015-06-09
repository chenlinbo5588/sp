{include file="common/header.tpl"}
<div class="login">
    {form_open(site_url('index/admin_login'))}
	    <div class="feedback">{$message}</div>
	    <div class="ui-btn ui-corner-all ui-icon-user ui-btn-icon-left">
	       <input type="text" name="username" value="{$smarty.post.username}" placeholder="请输入用户名"/>
	    </div>
	    <div class="ui-btn ui-corner-all ui-icon-lock ui-btn-icon-left">
	       <input type="password" name="password" placeholder="请输入密码"/>
	    </div>
	    <button type="submit" name="submit"  class="ui-btn-active">登陆</button>
    </form>
</div>

{include file="common/footer.tpl"}