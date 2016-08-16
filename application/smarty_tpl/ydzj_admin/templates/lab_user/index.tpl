{include file="common/lab_admin_header.tpl"}
    <div class="submenu">
        <ul>
            <li><a href="javascript:void(0)" class="selected">无</a></li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="center_content"> 
	    <div id="right_wrap">
		    <div id="right_content">
		      <div id="catelist" style="max-width:800px;">
		          <form name="categoryForm" method="get" action="{base_url('lab_user/index')}">
	               <div class="form">
	                    <div class="clearfix">
	                      <label>名称:</label>
	                      <input type="text" class="form_input" name="name" value="{$smarty.get.name}" placeholder="请输入实验员名称" />
	                      <input type="submit" class="form_submit" value="查询" />
	                      <a href="{base_url('lab_user/export/?')}{$queryStr}" title="导出到EXCEL">导出到EXCEL</a><span class="tip">&lt;&lt;&lt;请右键目标另存为</span></td>
	                    </div>
	                </div>
                </form>
                
			    <h2>实验员列表</h2> 
				<table class="rounded-corner">
				    <colgroup>
				        <col style="width:10%"/>
				        <col style="width:15%"/>
				        <col style="width:10%"/>
				        <col style="width:10%"/>
				        <col style="width:10%"/>
				        <col style="width:20%"/>
				        <col style="width:20%"/>
				    </colgroup>
				    <thead>
				        <tr>
				            <th>序号</th>
				            <th>登陆账号</th>
				            <th>名称</th>
				            <th>状态</th>
				            <th>录入人</th>
				            <th>录入时间</th>
				            <th>操作</th>
				        </tr>
				    </thead>
				    <tbody>
				        {foreach from=$data['data'] key=key item=item}
                        <tr id="row_{$item['id']}" {if $key % 2 == 0}class="odd"{else}class="even"{/if}>
                            <td>{$item['id']}</td>
                            <td>{$item['account']|escape}</td>
                            <td>{$item['name']|escape}</td>
                            <td>{$item['status']|escape}</td>
                            <td>{$item['creator']|escape}</td>
                            <td>{time_tran($item['gmt_create'])}</td>
                            <td>
                                <a href="{base_url('lab_user/edit/id/')}{$item['id']}">编辑</a>&nbsp;
                                <a class="delete" href="javascript:void(0);" data-href="{base_url('lab_user/delete/id/')}{$item['id']}" data-title="确定删除{$item['name']|escape}吗?">删除</a>&nbsp;
                            </td>
                        </tr>
                        {/foreach}  
				    </tbody>
				    <tfoot>
                        <tr>
                            <td colspan="7">{include file="pagination.tpl"}</td>
                        </tr>
                    </tfoot>
				</table>
			  </div>
		    </div><!-- end of right content-->
		</div><!-- end of right wrap -->
		{include file="./side.tpl"}           
{include file="common/lab_admin_footer.tpl"}