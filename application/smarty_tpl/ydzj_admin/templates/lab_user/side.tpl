		<div class="sidebar" id="sidebar">
		    <h2>功能菜单</h2>
	        <ul>
	            <li {if $action == 'add'} class="selected"{/if}><a href="{base_url('lab_user/add/')}" >添加实验员</a></li>
	            {if $action == 'edit'}<li class="selected"><a href="{base_url('lab_user/edit/id/')}{$info['id']}" >修改实验员</a></li>{/if}
	        </ul>
	    </div>
