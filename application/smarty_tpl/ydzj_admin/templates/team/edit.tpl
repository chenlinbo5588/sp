{include file="common/main_header.tpl"}
{config_load file="team.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('team')}" ><span>{#manage#}</span></a></li>
        <li><a href="{admin_site_url('team/add')}" ><span>{#add#}</span></a></li>
        <li><a href="javascript:void(0);" class="current"><span>{#edit#}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {$feedback}
  <div>
  	<ul class="tab-base">
        <li><a href="javascript:void(0);" nctype="index" class="current"><span>{#titel#}基本信息</span></a></li>
        <li><a href="javascript:void(0);" nctype="audit"><span>审核信息</span></a></li>
        <li><a href="javascript:void(0);" nctype="members"><span>成员信息</span></a></li>
        <li><a href="javascript:void(0);" nctype="games"><span>比赛记录</span></a></li>
        <li><a href="javascript:void(0);" nctype="consume"><span>消费记录</span></a></li>
      </ul>
  </div>
  
  {form_open_multipart(admin_site_url('team/edit?id='|cat:$info['basic']['id']),'name="form_index"')}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label>{#team_category#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="category_id">
                {foreach from=$sportsCategoryList item=item}
                <option value="{$item['id']}" {if $info['basic']['title'] == $item['name']}selected{/if}>{$item['name']}</option>
                {/foreach}
            </select>
            {form_error('category_id')}
          </td>
          <td class="vatop tips"></td>
        </tr>
      	<tr class="noborder">
          <td colspan="2" class="required"><label>{#team_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" class="txt" name="title" value="{$info['basic']['title']|escape}"/></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>{#slogan#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="text" name="slogan" class="txt" value="{$info['basic']['slogan']|escape}" placeholder="请输入建队口号"/>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>球队合照:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <img src="{resource_url($info['basic']['avatar_m'])}"/>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>修改球队合照:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="hidden" name="avatar_id" value="{$info['basic']['aid']}"/>
            <input type="hidden" name="old_avatar_id" value=""/>
            <input type="hidden" name="old_avatar" value=""/>
            <div class="upload"><input type='text' class="txt" name='avatar' id='avatar' value="{$info['basic']['avatar']}"/><input type="button" id="uploadButton" value="浏览" /></div>
            </td>
          <td class="vatop tips">支持格式jpg或者PNG 最小尺寸 {$teamImageConfig['h']['width']}x{$teamImageConfig['h']['height']}</td>
        </tr>
        <tr>
        	<td colspan="2" class="required"><div id="preview"></div></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>积分:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">积分&nbsp;<strong class="red">{$info['basic']['credits']}</strong>&nbsp;</td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {form_open(admin_site_url('team/audit'),'name="form_audit"')}
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>审核意见</label></td>
        </tr>
        <tr class="noborder">
        </tr>
        <tr>
          <td colspan="2" class="required"><label>审核备注</label></td>
        </tr>
        <tr class="noborder">
        	<td><textarea name="audit_remark"></textarea></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label>审核意见</label></td>
        </tr>
        <tr class="noborder">
        </tr>
        <tr>
          <td colspan="2" class="required"><label>审核意见</label></td>
        </tr>
        <tr class="noborder">
        </tr>
      </tbody>
    </table>
  </form>
  {form_open(admin_site_url('team/audit'),'name="form_members"')}
    <table class="tb-type2">
      <thead>
      	<tr>
      		<th></th>
      		<th>头像</th>
      		<th>真实名称</th>
      		<th>昵称</th>
      		<th>队内职务</th>
      		<th>场上位置</th>
      		<th>球衣号码</th>
      		<th>排序</th>
      	</tr>
      </thead>
      <tbody>
      	{foreach from=$info['members'] item=item key=key}
        <tr class="noborder">
          <td><input type="checkbox" name="sel" value="{$item['id']}"/></td>
          <td><img src="{resource_url($item['avatar_s'])}"/></td>
          <td>
          	<input type="hidden" name="uid" value="{$item['uid']|escape}" />
          	<input type="text" name="username" value="{$item['username']|escape}" />
          </td>
          <td>
          	<input type="text" name="nickname" value="{$item['nickname']|escape}" />
          </td>
          <td>
          	<input type="text" name="rolename" value="{$item['rolename']|escape}" />
          </td>
          <td>
          	<input type="text" name="position" value="{$item['position']|escape}" />
          </td>
          <td>
          	<input type="text" name="num" value="{$item['num']|escape}" />
          </td>
          <td>
          	<input type="text" name="displayorder" value="{$item['displayorder']|escape}" />
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </form>
  {include file="common/ke.tpl"}
  {include file="common/jcrop.tpl"}
  <script type="text/javascript">
	KindEditor.ready(function(K) {
		var uploadbutton = K.uploadbutton({
			button : K('#uploadButton')[0],
			fieldName : 'imgFile',
			extraParams : { formhash : formhash,min_width :{$teamImageConfig['h']['width']},min_height: {$teamImageConfig['h']['height']} },
			url : '{admin_site_url("common/pic_upload")}?mod=team_avatar',
			afterUpload : function(data) {
				refreshFormHash(data);
				if (data.error === 0) {
					K('#avatar').val(data.url);
					K('#preview').html('<img width="{$teamImageConfig['h']['width']}" height="{$teamImageConfig['h']['height']}" src="' + data.url + '"/>');
					
				} else {
					alert(data.msg);
				}
			},
			afterError : function(str) {
				alert('自定义错误信息: ' + str);
			}
		});
		uploadbutton.fileBox.change(function(e) {
			uploadbutton.submit();
		});
	});
			
			
	$(function(){
		$('.tab-base').find('a').bind('click',function(){
			$("#tag_tips").css('display','none');
			$('.tab-base').find('a').removeClass('current');
			$(this).addClass('current');
			
			$('form').css('display','none');
			$('form[name="form_'+$(this).attr('nctype')+'"]').css('display','');
		});
		
		$('form:gt(0)').css('display','none');
		
	});
	</script>
{include file="common/main_footer.tpl"}