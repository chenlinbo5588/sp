							 {form_open(admin_site_url('lab/manager_lab_user'),'name="memberForm"')}
							 <table class="rounded-corner" id="user_table">
							    <colgroup>
							        <col style="width:10%"/>
							        <col style="width:10%"/>
							        <col style="width:20%"/>
							        <col style="width:20%"/>
							        <col style="width:20%"/>
							        <col style="width:20%"/>
							    </colgroup>
							    <thead>
							        <tr>
							            <th>加入</th>
							            <th>移除</th>
							            <th>设为管理员</th>
							            <th>取消管理员</th>
							            <th>名称</th>
							            <th>登陆账号</th>
							        </tr>
							    </thead>
							    <tbody>
							        {foreach from=$data['data'] key=key item=item}
			                        <tr {if $key % 2 == 0}class="odd"{else}class="even"{/if}>
			                            <td>{if $memberList[$item['id']]}已加入{else}<input type="checkbox" name="user_id[]" value="{$item['id']}"/>{/if}</td>
			                            <td>{if $memberList[$item['id']]['can_drop']}<input type="checkbox" name="drop_user_id[]" value="{$item['id']}"/>{/if}</td>
			                            <td>{if $memberList[$item['id']]['is_manager'] == 'y'}已设置{else}{if (($isManager && $memberList[$item['id']]) || $userProfile['id'] == $smarty.const.LAB_FOUNDER_ID)}<input type="checkbox" name="be_manager[]" value="{$item['id']}"/>{/if}{/if}</td>
			                            {* 本人添加的成员，如果是管理， 则将其加入的管理员有权取消其管理员 *}
			                            <td>{if $memberList[$item['id']]['is_manager'] == 'y' && $memberList[$item['id']]['can_drop_manager']}<input type="checkbox" name="drop_manager[]" value="{$item['id']}"/>{/if}</td>
			                            <td>{$item['name']|escape}</td>
			                            <td>{$item['account']|escape}</td>
			                        </tr>
			                        {/foreach}
							    </tbody>
							    <tfoot>
			                        <tr>
			                            <td colspan="7">{include file="common/pagination.tpl"}</td>
			                        </tr>
			                    </tfoot>
							</table>
                            </form>