		<div class="sidebar" id="sidebar">
		    <h2>功能菜单</h2>
	        <ul>
	            <li {if $action == 'add'} class="selected"{/if}><a href="{base_url('lab_goods/add')}" >添加货品</a></li>
	            {if $action == 'edit'}<li class="selected"><a href="{base_url('lab_goods/edit/id/')}{$info['id']}" >修改货品</a></li>{/if}
	            <li {if $action == 'import'} class="selected"{/if}><a href="{base_url('lab_goods/import')}" >导入货品</a></li>
	            {if $userProfile['is_manager'] == 'y'}<li {if $action == 'empty_goods'} class="selected"{/if}><a href="{base_url('lab_goods/empty_goods')}">清空货品</a></li>{/if}
	        </ul>
	    </div>
	    
