<!DOCTYPE html>
<html lang="en">
<head>
    {include file="common/wxheader.tpl"}
</head>
<body>
    <h1 class="title">{$siteSetting['site_name']|escape}绑定流程</h1>
    <form action="{site_url('bind/step2')}" method="post" onsubmit="return validation(this);">
        <input type="hidden" name="openid" value="{$openid}"/>
        <table>
            <tr>
                <td>手机号码</td>
                <td><input type="text" name="mobile" value="{$smarty.post.mobile}" placeholder="请填入手机号码"/>{form_error('mobile')}</td>
            </tr>
            <tr>
                <td>虚拟号码(选填)</td>
                <td><input type="text" name="virtual_no" value="{$smarty.post.virtual_no}" placeholder="请填入虚拟号码"/>{form_error('virtual_no')}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="submit" class="btn_blue" style="font-size:16px;" value="确认绑定"/></td>
             </tr>
        </table>
    </form>
    <script>
        function validation(obj){
            var isMobileReg = /^(13[0-9]|15[0-9]|18[8|9])\d+$/;
            if(obj['mobile'].value.length != 13 && !isMobileReg.test(obj['mobile'].value)){
                alert("请输入正确的手机号码");
                return false;
            }
            
            if(obj['mobile'].value != ''){
                if(!/^\d+$/.test(obj['mobile'].value)){
                    alert("虚拟号码必须为数字");
                    return false;
                }
            }
            
            return true;
        }
    </script>
    {include file="common/wxfooter.tpl"}
</body>
</html>