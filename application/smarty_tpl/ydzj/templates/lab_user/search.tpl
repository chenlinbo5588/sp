							 {form_open(site_url('lab/manager_lab_user'),'name="memberForm"')}
							 <table class="fulltable" id="user_table">
							    <thead>
							        <tr>
							            <th>踢出</th>
							            <th>设为/取消管理员</th>
							            <th>登陆账号</th>
							            <th>邮箱地址</th>
							        </tr>
							    </thead>
							    <tbody>
							        {foreach from=$memberList key=key item=item}
			                        <tr>
			                            <td>{if $item['can_drop']}<input type="checkbox" name="drop_user_id[]" value="{$item['uid']}"/>{/if}</td>
			                            <td>
			                            	{if $profile['basic']['uid'] == $item['oid']}已设置
			                            	{else}
			                            		{if $item['is_manager'] == 'y'}
			                            			{if $item['can_drop_manager']}
			                            			<input type="checkbox" name="drop_manager[]" value="{$item['uid']}"/>
			                            			{/if}
			                            		{else}
			                            		<input type="checkbox" name="be_manager[]" value="{$item['uid']}"/>
			                            		{/if}
			                            	{/if}
			                           	</td>
			                            <td>{$item['username']|escape}</td>
			                            <td>{$item['email']|escape}</td>
			                        </tr>
			                        {/foreach}
							    </tbody>
							    <tfoot>
			                        <tr>
			                            <td colspan="4">{include file="common/pagination.tpl"}</td>
			                        </tr>
			                    </tfoot>
							</table>
                            </form>