		<div class="sidebar" id="sidebar">
		    <h2>功能菜单</h2>
	        <ul>
	        
	            <li {if $act == 'add'}class="selected"{/if}><a href="{base_url('lab_admin/add')}">添加实验室</a></li>
	            {if $act == 'edit'}<li class="selected"><a href="{base_url('lab_admin/edit/id/')}{$info['id']}">修改实验室</a></li>{/if}
	            {if $action == 'index'}
	            <li><a href="{base_url('lab_admin/export')}">导出实验室</a></li>
	            {/if}
	        </ul>
	        
	        
	        
	    </div>
