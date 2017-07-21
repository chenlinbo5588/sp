        <tr>
          <td colspan="2" class="required"><label class="validation" for="passwd">密码:</label>{form_error('passwd')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="passwd" value="{$info['passwd']}" name="passwd" class="txt"></td>
          <td class="vatop tips">密码长度6~12位,英文字母、数字、特殊符号</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="passwd2">密码确认:</label>{form_error('passwd2')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="passwd2" value="{$info['passwd2']}" name="passwd2" class="txt"></td>
          <td class="vatop tips">密码长度6~12位,英文字母、数字、特殊符号</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="village_id">所在村:</label>{form_error('village_id')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="dept_id">
          		<option value="">请选择</option>
          		{foreach from=$deptList item=item}
          		<option value="{$item['id']}" {if $info['dept_id'] == $item['id']}selected{/if}>{$item['name']|escape}</option>
          		{/foreach}
          	</select>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="email">电子邮箱:</label>{form_error('email')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text"  id="email" value="{$info['email']|escape}" name="email" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>性别:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul>
              <li>
                <label><input type="radio" checked="checked" value="S" {if $info['sex'] == 'S'}checked{/if} name="sex">保密</label>
              </li>
              <li>
                <label><input type="radio" value="M" {if $info['sex'] == 'M'}checked{/if} name="sex">男</label>
              </li>
              <li>
                <label><input type="radio" value="F" {if $info['sex'] == 'F'}checked{/if} name="sex">女</label>
              </li>
            </ul></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="qq">QQ:</label>{form_error('qq')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['qq']|escape}" id="qq" name="qq" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="weixin">微信号:</label>{form_error('weixin')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['weixin']|escape}" id="weixin" name="weixin" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>头像:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="hidden" name="avatar_id" value="{$info['aid']}"/>
            <input type="hidden" name="old_avatar_id" value=""/>
            <input type="hidden" name="old_avatar" value=""/>
            <div class="upload">
              <input type='text' name='avatar' id='avatar' value="{$info['avatar']}" class='txt' />
              <input type="button" id="uploadButton" value="浏览" />
            </div>
            </td>
          <td class="vatop tips">支持格式jpg,文件最大不要超过1M,最小尺寸 {$avatarImageSize['m']['width']}x{$avatarImageSize['m']['height']}</td>
        </tr>
        <tr>
        	<td colspan="2"><div id="previewWrap"></div></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>允许登录:</label>{form_error('freeze')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
            <label for="freeze_1" class="cb-enable{if $info['freeze'] == 'N'} selected{/if}" ><span>允许</span></label>
            <label for="freeze_2" class="cb-disable{if $info['freeze'] == 'Y'} selected{/if}" ><span>禁止</span></label>
            <input id="freeze_1" name="freeze" value="N" {if $info['freeze'] == 'N'}checked{/if} type="radio"/>
            <input id="freeze_2" name="freeze" value="Y" {if $info['freeze'] == 'Y'}checked{/if} type="radio"/></td>
          <td class="vatop tips"></td>
        </tr>