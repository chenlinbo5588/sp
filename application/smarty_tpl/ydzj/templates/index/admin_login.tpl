{include file="common/header.tpl"}
<div class="login">

    {form_open(site_url('index/admin_login'))}
        <table>
            <tr><td><div class="feedback">{$message}</div></td></tr>
            <tr><td><label>用户名</label></td></tr>
            <tr><td><input type="text" name="username" placeholder="请输入用户名"/></td></tr>
            <tr><td><label>密码</label></td></tr>
            <tr><td><input type="password" name="password" placeholder="请输入密码"/></td></tr>
            <tr><td><input type="submit" name="submit" value="登陆"/></td></tr>
        </table>
    </form>
</div>

{include file="common/footer.tpl"}